<div id="fh5co-contact" class="animate-box">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3 class="section-title">Contact Us</h3>
                <p>We do not have a physical address, but focus on Southwestern Indiana as a whole. Our main
                    team is based out of Evansville.</p>
                <ul class="contact-info">
                    <li><i class="icon-location-pin"></i>Evansville, IN, USA</li>
                    <li><i class="icon-mail"></i><a href="mailto:info@swinpan.org">info@swinpan.org</a></li>
                    <li><i class="icon-globe2"></i><a href="index.php">www.swinpan.org</a></li>
                </ul>
            </div>

            <?php

            // Did we Submit the Form
            if (isset($_POST['submit'])) {
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

                $headers[] = "MIME-Version: 1.0";
                $headers[] = "Content-type: text/html; charset=iso-8859-1";
                $headers[] = "From: $name <$email>";
                $headers[] = "To: SWIN PAN <info@swinpan.org>";


                mail("info@swinpan.org", "Contact Form Submission", $message, implode("\r\n", $headers));

            ?>
            <div class="col-md-6">
                <div class="row">
                    <p><strong>Thank you for contacting us! We will try to respond as soon as we can!</strong></p>
                </div>
            </div>
            <?php } else { ?>
            <form method="post" action="index.php?p=contact">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                        <textarea name="message" class="form-control" id="" cols="30" rows="7"
                                                  placeholder="Message"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" name="submit" value="Send Message" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
<!-- END fh5co-contact -->
<div id="map" class="fh5co-map"></div>
<!-- END map -->

<!-- Google Map -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCefOgb1ZWqYtj7raVSmN4PL2WkTrc-KyA&sensor=false"></script>
<script src="js/google_map.js"></script>