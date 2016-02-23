/*!
 * jQuery intlKeyboard Plugin v0.1
 *
 * Copyright 2013 Ashwin Purohit and other contributors
 * Released under the MIT license
 *
 * Date: 24 Aug 2013
 */
(function($) {
    // Cross-browser set caret position function
    // From http://stackoverflow.com/a/512542/100208
    var setCaretPosition = function(el, caretPos) {
        if (el != null) {
            if (el.createTextRange) {
                var range = el.createTextRange();
                range.move("character", caretPos);
                range.select();
            } else {
                if (el.selectionStart) {
                    el.focus();
                    el.setSelectionRange(caretPos, caretPos);
                } else {
                    el.focus();
                }
            }
        }
    };
    // Cross-browser get caret position function
    // From http://stackoverflow.com/a/4931963/100208
    var getInputSelection = function(el) {
        var start = 0, end = 0, normalizedValue, range, textInputRange, len, endRange;
        if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
            start = el.selectionStart;
            end = el.selectionEnd;
        } else {
            range = document.selection.createRange();
            if (range && range.parentElement() == el) {
                len = el.value.length;
                normalizedValue = el.value.replace(/\r\n/g, "\n");
                // Create a working TextRange that lives only in the input
                textInputRange = el.createTextRange();
                textInputRange.moveToBookmark(range.getBookmark());
                // Check if the start and end of the selection are at the very end
                // of the input, since moveStart/moveEnd doesn't return what we want
                // in those cases
                endRange = el.createTextRange();
                endRange.collapse(false);
                if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                    start = end = len;
                } else {
                    start = -textInputRange.moveStart("character", -len);
                    start += normalizedValue.slice(0, start).split("\n").length - 1;
                    if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                        end = len;
                    } else {
                        end = -textInputRange.moveEnd("character", -len);
                        end += normalizedValue.slice(0, end).split("\n").length - 1;
                    }
                }
            }
        }
        return {
            start: start,
            end: end
        };
    };
    // Usage: call this on any input or textarea:
    // $('input.french').intlKeyboard({
    //     language: 'fra',
    //     replacements: '#suggestions' // Only makes sense for IMEs
    //     numResults: 5 // Only makes sense for IMEs
    // });
    // This will listen for changes, and replace certain key sequences with
    // international versions, for people who don't want to install a keyboard.
    $.fn.intlKeyboard = function(options) {
        // Keys that don't change caret position in input, but that
        // are used in key combinations
        var silentKeys = {
            16: true
        };
        // A reversed version of http://www.selfcontained.us/2009/09/16/getting-keycode-values-in-javascript/
        var keysToCodes = {
            "backspace":8, "tab":9, "return":13, "shift":16, "ctrl":17, "alt":18, "pausebreak":19, "capslock":20, "escape":27, " ":32, "pageup":33,
            "pagedown":34, "end":35, "home":36, "left":37, "up":38, "right":39, "down":40, "+":43, "printscreen":44, "insert":45, "delete":46,
            "0":48, "1":49, "2":50, "3":51, "4":52, "5":53, "6":54, "7":55, "8":56, "9":57, ";": 59,
            "=":61, "a":65, "b":66, "c":67, "d":68, "e":69, "f":70, "g":71, "h":72, "i":73, "j":74, "k":75, "l":76,
            "m":77, "n":78, "o":79, "p":80, "q":81, "r":82, "s":83, "t":84, "u":85, "v":86, "w":87, "x":88, "y":89, "z":90,
            // Numpad codes -- TODO keys that, depending on if hit from numpad
            // or keyboard, can have different codes, like: "0": [48, 96]
            //"0":96, "1":97, "2":98, "3":99, "4":100, "5":101, "6":102, "7":103, "8":104, "9":105,
             "*":106, "+":107, "-":109, ".":110, "/":111,
            "f1":112, "f2":113, "f3":114, "f4":115, "f5":116, "f6":117, "f7":118, "f8":119, "f9":120, "f10":121, "f11":122, "f12":123,
            "numlock":144, "scrolllock":145, ";":186, "=":187, ",":188, "-":189, ".":190, "/":191, "`":192, "[":219, "\\":220, "]":221, "'": 222
        };
        var stack = [];
        var self = this;
        // Ideographic languages that we'd prefer to use Google suggest for.
        var languageToGoogleITC = {
            zho: "zh-t-i0-pinyin"
        };
        // We define most Latin keyboard mappings ourselves.
        var langReplacements = {
            fra: [ // French | Français
                // C cedille
                [ [",", "c"], "ç" ],
                [ [",", "shift", "c"], "Ç" ],
                // Accent grave
                [ [ "`", "a"], "à" ],
                [ [ "`", "e"], "è" ],
                [ [ "`", "u"], "ù" ],
                [ [ "`", "shift", "a"], "À" ],
                [ [ "`", "shift", "e"], "È" ],
                [ [ "`", "shift", "u"], "Ù" ],
                // Accent aigu
                [ [ "'", "e"], "é" ],
                [ [ "'", "shift", "e"], "É" ],
                // But, differentiate between C'est and Céline
                // by allowing a user to put a space after the ' to not accent
                [ [ "'", " ", "e"], "'e" ],
                [ [ "'", " ", "shift", "e"], "'E" ],
                // Circonflexe (^ = [Shift] + 6)
                [ [ "shift", "6", "a"], "â" ],
                [ [ "shift", "6", "e"], "ê" ],
                [ [ "shift", "6", "i"], "î" ],
                [ [ "shift", "6", "o"], "ô" ],
                [ [ "shift", "6", "shift", "a"], "Â" ],
                [ [ "shift", "6", "shift", "e"], "Ê" ],
                [ [ "shift", "6", "shift", "i"], "Î" ],
                [ [ "shift", "6", "shift", "o"], "Ô" ],
                // Le tréma
                [ [ "shift", "'", "a"], "ä" ],
                [ [ "shift", "'", "e"], "ë" ],
                [ [ "shift", "'", "i"], "ï" ],
                [ [ "shift", "'", "o"], "ö" ],
                [ [ "shift", "'", "u"], "ü" ],
                [ [ "shift", "'", "shift", "a"], "Ä" ],
                [ [ "shift", "'", "shift", "e"], "Ë" ],
                [ [ "shift", "'", "shift", "i"], "Ï" ],
                [ [ "shift", "'", "shift", "o"], "Ö" ],
                [ [ "shift", "'", "shift", "u"], "Ü" ]
            ],
            esp: [ // Spanish | Español
                // El acento
                [ [ "'", "a"], "á" ],
                [ [ "'", "e"], "é" ],
                [ [ "'", "i"], "í" ],
                [ [ "'", "o"], "ó" ],
                [ [ "'", "u"], "ú" ],
                [ [ "'", "shift", "a"], "Á" ],
                [ [ "'", "shift", "e"], "É" ],
                [ [ "'", "shift", "i"], "Í" ],
                [ [ "'", "shift", "o"], "Ó" ],
                [ [ "'", "shift", "u"], "Ú" ],
                // La crema o diéresis
                [ [ "shift", "'", "u"], "ü" ],
                [ [ "shift", "'", "shift", "u"], "Ü"],
                // La ñ
                [ [ "shift", "`", "n"], "ñ"],
                [ [ "shift", "`", "shift", "n"], "Ñ"],
                // Signos de interrogación y exclamación invertidos
                // Handles [Shift]-[?]-[?], and [Shift]-[?], [Shift]-[?]
                [ [ "shift", "/", "/"], "¿" ],
                [ [ "shift", "/", "shift", "/"], "¿" ],
                [ [ "shift", "1", "1"], "¡" ],
                [ [ "shift", "1", "shift", "1"], "¡" ],
            ],
            deu: [ // German | Deutsch
                // Umlaut
                [ [ "shift", "'", "a"], "ä" ],
                [ [ "shift", "'", "o"], "ö" ],
                [ [ "shift", "'", "u"], "ü" ],
                [ [ "shift", "'", "shift", "a"], "Ä"],
                [ [ "shift", "'", "shift", "o"], "Ö"],
                [ [ "shift", "'", "shift", "u"], "Ü"],
                // Eszett
                [ [",", "s"], "ß" ],
            ],
            tur: [ // Turkish | Türkçe
                // Yumuşak ge
                [ [ ",", "g"], "ğ" ],
                [ [ ",", "shift", "g"], "Ğ" ],
                // ı
                [ [ ".", "i"], "ı" ],
                [ [ ".", "shift", "i"], "I" ],
                // Capital i becomes İ
                [ [ "shift", "i"], "İ" ],
                // â, î, û
                [ [ "shift", "6", "a"], "â" ],
                [ [ "shift", "6", "i"], "î" ],
                [ [ "shift", "6", "u"], "û" ],
                [ [ "shift", "6", "shift", "a"], "Â" ],
                [ [ "shift", "6", "shift", "i"], "Î" ],
                [ [ "shift", "6", "shift", "u"], "Û" ],
                // çe
                [ [ ",", "c"], "ç" ],
                [ [ ",", "shift", "c"], "Ç" ],
                // şe
                [ [ ",", "s"], "ş" ],
                [ [ ",", "shift", "s"], "Ş" ],
                // ö, ü
                [ [ "shift", "'", "o"], "ö" ],
                [ [ "shift", "'", "u"], "ü" ],
                [ [ "shift", "'", "shift", "o"], "Ö"],
                [ [ "shift", "'", "shift", "u"], "Ü"],
            ]
        };
        // Builds a reverse trie for key combinations, keyed on keyCode,
        // from the passed human-readable array of arrays.
        // If the combo "," (188) then "c" (67), should be replaced with ç,
        // then the trie would look like: {67: {188: {r: ç}}}
        var reverseTrieForLang = function(language) {
            var tree = {};
            var combos = langReplacements[language];
            for (var i = 0; i < combos.length; i += 1) {
                var node = tree;
                var keys = combos[i][0];
                var replacement = combos[i][1];
                for (var j = keys.length - 1; j >= 0; j -= 1) {
                    var key = keys[j];
                    var code = keysToCodes[key];
                    if (!node.hasOwnProperty(code)) {
                        node[code] = {};
                    }
                    node = node[code];
                    if (j == 0) {
                        node.r = replacement;
                    }
                }
            }
            return tree;
        };
        // If this language is Pinyin, the IME is completely different.
        if (languageToGoogleITC.hasOwnProperty(options.language)) {
            // Default number of results.
            options = $.extend({
                numResults: 5
            }, options);
            var strokeBuffer = [];
            var replacementDiv = $(options.suggestions);
            var replacementUl = replacementDiv.find(".suggestions");
            var replacementTyped = replacementDiv.find(".typed");
            var replacements = [];
            var mouseOnReplacement = null;
            // When a user selects a replacement. We're guaranteed that
            // index is valid for a replacement.
            var selectReplacement = function(index) {
                var decodedReplacement = decodeURIComponent(replacements[index]);
                var caret = getInputSelection(self[0]);
                var pos = caret.end;
                var text = self.val();
                // Insert the chosen suggestion at the current position.
                var replacementText = text.substr(0, pos) + decodedReplacement + text.substr(pos);
                self.val(replacementText);
                // When you replace text, the cursor auto-jumps to the end.
                // If you were in the middle of a string, that's annoying.
                // This puts the cursor back to the character you were editing.
                setCaretPosition(self[0], pos + decodedReplacement.length);
                clearSelection();
            };
            // Hide and clear the replacement divs and suggestions.
            var clearSelection = function() {
                mouseOnReplacement = null;
                strokeBuffer.length = 0;
                replacements = [];
                replacementUl.empty();
                replacementTyped.empty();
                replacementDiv.hide();
            };
            // Sends a request to Google to get IME suggestions
            var getReplacements = function() {
                // Show or hide the replacement div (in case called backspace)
                if (strokeBuffer.length > 0) {
                    replacementDiv.show();
                } else {
                    clearSelection();
                    return;
                }
                var encodedText = encodeURIComponent(strokeBuffer.join(""));
                replacementTyped.html(encodedText);
                $.ajax({
                    type: "GET",
                    url: "https://inputtools.google.com/request",
                    cache: false,
                    dataType: "json",
                    data: {
                        text: encodedText,
                        itc: languageToGoogleITC[options.language],
                        num: options.numResults,
                        // Google sets these values in its own AJAX calls, but they're
                        // cryptic and undocumented, so I'm passing them along.
                        cp: 0,
                        cs: 1,
                        // Input and output encoding
                        ie: "utf-8",
                        oe: "utf-8",
                        // Doesn't seem to matter what this is.
                        app: "test"
                    }
                }).done(function(result) {
                    // The Google JSON result is terribly nested.
                    replacements = result[1][0][1];
                    var listElements = "";
                    // Ordered lists don't retain numbering when making the
                    // list horizontal with li{display:inline}
                    for (var i = 0; i < replacements.length; i += 1) {
                        listElements += "<li><span class='number'>" + (i + 1) + ".</span> " + replacements[i] + "</li>";
                    }
                    replacementUl.html(listElements);
                });
            };
            // On delete/backspace, drop the last char from strokeBuffer,
            // and update replacements
            this.bind("keydown", function(e) {
                if (e.which === 8 || e.which === 46) {
                    if (strokeBuffer.length !== 0) {
                        e.preventDefault();
                        strokeBuffer.pop();
                        getReplacements();
                    }
                }
            });
            // Keypress on input gets more Google Input Tools suggestions
            this.bind("keypress", function(e) {
                var charCode = String.fromCharCode(e.charCode);
                var index = parseInt(charCode, 10) - 1;
                // Hit a number from 1 to replacements.length
                if (!isNaN(index)) {
                    e.preventDefault();
                    if (index >= 0 && index < replacements.length) {
                        selectReplacement(index);
                    }
                    return;
                }
                // Hit [Space] or [Enter] -- just pick the first replacement.
                if (charCode === "" || charCode === " ") {
                    if (strokeBuffer.length !== 0) {
                        e.preventDefault();
                        selectReplacement(0);
                    }
                    return;
                }
                // Hit any a-z character. Don't show the
                // character in the input.
                if (charCode >= "a" && charCode <= "z") {
                    e.preventDefault();
                    strokeBuffer.push(charCode);
                    getReplacements();
                }
            });
            // When a user clicks a replacement with the mouse
            replacementUl.on("click", "li", function(e) {
                selectReplacement($(e.target).index());
            });
            // If the user focuses out of the input element, kill
            // the suggestion box. But if they clicked on a suggestion,
            // which also triggers an input.focusout event, set
            // set the replacement and re-focus the input.
            this.bind("focusout", function() {
                if (mouseOnReplacement !== null) {
                    selectReplacement(mouseOnReplacement);
                    $(this).focus();
                } else {
                    clearSelection();
                }
            });
            replacementUl.on("mouseover", "li", function(e) {
                var index = $(e.target).index();
                mouseOnReplacement = index;
            });
            // Make it chainable.
            return this;
        }
        // If the language is Latin-extended, we can use keystroke replacement
        var reverseTrie = reverseTrieForLang(options.language);
        this.bind("keydown", function(e) {
            var text = self.val();
            var code;
            // The trie of replacements for this language.
            var node = reverseTrie;
            // The current caret start and end position.
            var caret = getInputSelection(this);
            var replacement = null;
            // Number of keystrokes to 'erase' when replacing.
            var sequenceLength = 0;
            // On every keydown, add the keycode to the stack.
            stack.push(e.which);
            for (var i = stack.length - 1; i >= 0; i -= 1) {
                code = stack[i];
                // Don't count silent keys like [Shift] in sequence length.
                if (!silentKeys.hasOwnProperty(code)) {
                    sequenceLength += 1;
                }
                node = node[code];
                // Couldn't "recurse" because the sequence trail ends here.
                if (node === undefined) {
                    break;
                }
                // Found a character replacement.
                // Peek one more character, and if the sequence continues,
                // don't use the replacement yet.
                if (i > 0 && node.hasOwnProperty(stack[i - 1])) {
                    continue;
                }
                if (node.hasOwnProperty("r")) {
                    replacement = node.r;
                    break;
                }
            }
            // Found a replacement sequence.
            // Use it to replace the (sequenceLength) chars before current caret position
            if (replacement !== null) {
                e.preventDefault();
                // TODO: Use a stack of length [max combo length for language];
                // this one grows ad infinitum (er, ad successus), before clearing.
                stack.length = 0;
                var pos = caret.end;
                var replacementText = text.substr(0, pos - sequenceLength + 1) + replacement + text.substr(pos);
                self.val(replacementText);
                // When you replace text, the cursor auto-jumps to the end.
                // If you were in the middle of a string, that's annoying.
                // This puts the cursor back to the character you were editing.
                setCaretPosition(this, pos);
            }
        });
        // Make it chainable
        return this;
    };
})(jQuery);
