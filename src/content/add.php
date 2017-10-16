<?php

// If we Submitted, Add the Info
if (isset($_POST['submit'])) {

    // Setup Error Handling.
    $error = false;
    $error_messages = array();

    $post_data = Organization::filter();
    $org = new Organization($post_data);

    // Check For Required Data
    // Empty Name
    if (empty($post_data['name'])) {
        $error = true;
        $error_messages[] = "You must enter a <strong>name</strong> for your group.";
    }
    // Empty Description
    if (empty($post_data['description'])) {
        $error = true;
        $error_messages[] = "You must enter a <strong>description</strong> for your group.";
    }
    // Description > 500 Characters
    if (strlen($post_data['description']) > 500) {
        $error = true;
        $error_messages[] = "Your <strong>description</strong> must be 500 characters or less.";
    }
    // Empty Contact Name
    if (empty($post_data['contact_first_name']) || empty($post_data['contact_last_name'])) {
        $error = true;
        $error_messages[] = "You must enter a <strong>contact name</strong> to serve as the point-of-contact for your group.";
    }
    // No Way to Contact
    if (empty($post_data['website']) && empty($post_data['facebook']) && empty($post_data['twitter']) && empty($post_data['youtube']) && empty($post_data['meetup']) && empty($post_data['contact_phone']) && empty($post_data['contact_email'])) {
        $error = true;
        $error_messages[] = "You have not entered any website, social media, phone number, or email for <strong>contacting</strong> your group. Please enter at least one of these so that other groups can contact you.";
    }
    // Empty Tags
    if (empty($post_data['tags'])) {
        $error = true;
        $error_messages[] = "You must select at least one <strong>interest tag</strong> in order for people to search for your group.";
    }

    // If there is no error, try submitting.
    if (!$error) {

        // Attempt to Add the Organization
        $result = addDatabaseEntry($org);

        // If Success, Add Tags and Output Message
        if ($result->success) {
            addOrgTags((int)$result->new_id, $post_data['tags']);

            // Email Upon Success
            $to = "Admin <info@swinpan.org>";
            $subject = "New Application: " . $org->getName();
            $message = "<html>
                    <head><title>New Organization Application</title></head>
                    <body>
                    <p>Hello SWIN PAN administrators. A new organization has applied to be part of our network. Please review the group.</p>
                    <table style='width:800px;'>
                        <tbody>
                            <tr>
                                <td style='font-weight:bold;'>Organization</td>
                                <td>" . $org->getName() . "</td>
                                <td style='font-weight:bold;'>Website</td>
                                <td>" . $org->getWebsite() . "</td>
                            </tr>
                            <tr>
                                <td style='font-weight:bold;'>Contact Name</td>
                                <td>" . $org->getContactFirstName() . " " . $org->getContactLastName() . "</td>
                                <td style='font-weight:bold;'>Email</td>
                                <td>" . $org->getContactEmail() . "</td>
                            </tr>
                            <tr>
                                <td style='font-weight:bold;'>Phone</td>
                                <td>" . $org->getContactPhone() . "</td>
                                <td colspan='2'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan='4'><strong>Description</strong>: " . $org->getDescription() . "</td>
                            </tr>
                        </tbody>
                    </table>
                    </body>
                    </html>";
            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-type: text/html; charset=iso-8859-1';
            $headers[] = 'From: No Reply <noreply@swinpan.org>';

            // Send the Mail
            mail($to, $subject, $message, implode("\r\n", $headers));

            // Display Success Message
            ?>

            <div id="fh5co-contact" class="animate-box">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                            <h3>Thank You!</h3>
                            <p>Thank you for submitting your organization for inclusion in the Southwestern Indiana
                                Partnership
                                Action Network! We will review your submission and contact you if there are any
                                questions and/or to
                                alert you that your organization has been approved! Thank you again for your interest in
                                joining.</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php

            // End Display of Success
        } else {

            // Display Failure

            ?>

            <div id="fh5co-contact" class="animate-box">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                            <h3>Oh No!</h3>
                            <p>We apologize, but it seems there was an error submitting your organization for inclusion
                                in
                                the Southwestern Indiana Partnership Action Network. Please try submitting again, or
                                contact
                                <a href="mailto:info@swinpan.org">info@swinpan.org</a> for help.</p>
                        </div>
                    </div>
                </div>
            </div>

            <?php

            // End Display of Failure
        }
    } else {

        // Display Error Message
        ?>

        <div id="fh5co-contact" class="animate-box">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
                        <h3>Houston, We Have a Problem!</h3>
                        <p>Oops! it looks like you forgot something when submitting your form! Please see the messages below
                            and try resubmitting, or contact <a href="mailto:info@swinpan.org">info@swinpan.org</a> for help.</p>
                        <?php foreach ($error_messages as $m) { echo "<p style='text-align:left;'><i class='fa fa-exclamation-circle' aria-hidden='true'></i> " . $m . "</p>" . PHP_EOL; } ?>
                        <p><a href="javascript:history.back();">Click here</a> to go back and try again.</p>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

} else {

    // Display Form Page

?>
<div id="fh5co-contact" class="animate-box">
    <div class="container">
        <form method="post" action="index.php?p=add">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="section-title">Submit Your Organization / Group</h2>
                    <p>Use this form to submit your organization's information to become part of the SWIN Partnership
                        Action Network. Once submitted, we will contact you using the information you provided to ensure
                        your group is active before it is approved to appear in the directory. Once your organization is
                        approved, you may claim to be part of the network, utilize our logo, and connect with more active
                        members and volunteers! Thank you for submitting your organization!</p>
                </div>
                <div class="col-md-12">
                    <h3 class="section-title">Basic Information</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Organization Name*" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="url" name="website" class="form-control" placeholder="Website (include http://)">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="description" class="form-control" id="" cols="30" rows="7" maxlength="500" placeholder="Description of Your Organization* (Limited to 500 Characters)" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <select name="tags[]" id="tags" data-placeholder="Tag Your Group's Main Interests or Purpose..." class="form-control" title="Tags" multiple="multiple">
                                    <?php

                                        foreach(getTags() as $tag) {
                                            echo "<option value='$tag->tag_id'>$tag->name</option>";
                                        }

                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <h3 class="section-title">Contact Information</h3>
                    <p>Phone Numbers are optional and will never be shared with anyone for purposes not related to contacting for organizational purposes.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="contact_first_name" class="form-control" placeholder="First Name*" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="contact_last_name" class="form-control" placeholder="Last Name*" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="contact_phone" pattern="^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}$" class="form-control" placeholder="Phone (XXX) XXX-XXXX">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="email" name="contact_email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                    </div>
                    <h3 class="section-title">Address Information</h3>
                    <p>Address information is optional. Street Addresses should be for official correspondence, and are usually not home addresses.
                        If a county is selected, please select one as your primary county of operation, or leave blank.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="address_street" class="form-control" placeholder="Street Address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="address_city" class="form-control" placeholder="City">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" name="address_zipcode" maxlength="5" class="form-control" placeholder="ZIP Code">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input list="counties" name="address_county" maxlength="11" class="form-control" placeholder="Primary County of Operation">
                                <datalist id="counties">
                                    <option value="Daviess">
                                    <option value="Dubois">
                                    <option value="Gibson">
                                    <option value="Knox">
                                    <option value="Martin">
                                    <option value="Perry">
                                    <option value="Pike">
                                    <option value="Posey">
                                    <option value="Spencer">
                                    <option value="Vanderburgh">
                                    <option value="Warrick">
                                </datalist>
                            </div>
                        </div>
                    </div>
                    <h3 class="section-title">Social Media</h3>
                    <p>Please use full links (including http:// or https://) for all links and social media.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="url" name="facebook" class="form-control" placeholder="Facebook">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="url" name="twitter" class="form-control" placeholder="Twitter">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="url" name="youtube" class="form-control" placeholder="YouTube">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="url" name="meetup" class="form-control" placeholder="MeetUp">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="submit" name="submit" value="Submit Information" class="btn btn-primary">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php } ?>