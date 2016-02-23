<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/admin/css/style.css">
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>        
        <script>
            $(document).ready(function() {
                $('#country').change(function () {
                $.ajax({
                    type: 'POST',
                    data: { c : $(this).val()},
                    url: "<?php echo base_url(); ?>mdcities",
                    cache: false,
                    success: function (data) {
                        $('.cities').replaceWith(data);
                    }
                });
            });
            });
        </script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        
        <script type="text/javascript" src="https://www.google.com/jsapi">
        </script>
        <script type="text/javascript">
            
          // Load the Google Transliterate API
          google.load("elements", "1", {
                packages: "transliteration"
              });

          function onLoad() {
            var options = {
                sourceLanguage:
                    google.elements.transliteration.LanguageCode.ENGLISH,
                destinationLanguage:
                            [google.elements.transliteration.LanguageCode.ARABIC],
                    shortcutKey: 'ctrl+g',
                    transliterationEnabled: true
                };

                // Create an instance on TransliterationControl with the required
                // options.
                var control =
                        new google.elements.transliteration.TransliterationControl(options);

                // Enable transliteration in the textbox with id
                // 'transliterateTextarea'.
                control.makeTransliteratable(['trans']);
            }
            google.setOnLoadCallback(onLoad);
        </script>


    </head>
    <body>
        <div class="container">
            <div class="row">
                <header id="header">
                    <nav class="main-nav">
                        <ul>
                            <?php if (empty($this->session->userdata('username'))) { ?>
                                    <!--<li><a href="<?php echo base_url(); ?>">Login</a></li>-->                                        
                            <?php } else { ?>
                                <li><a href="<?php echo base_url(); ?>dashboard">Home</a></li>
                                <li><?php echo '<b>Welcome </b>' . $this->session->userdata('username'); ?></li>
                                <li><a href="<?php echo base_url(); ?>admin/logout">Logout</a></li>
                            <?php } ?>
                        </ul>
                    </nav>
                </header>