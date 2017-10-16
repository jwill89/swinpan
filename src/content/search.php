<?php
// If Search is New, Clear Session
if (filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING) == 'new') {
    unset($_SESSION['tags']);
}

if (isset($_POST['submit']) || (isset($_SESSION['tags']) && is_array($_SESSION['tags']))) {

    // If We Submitted, Set the Tags. If we didn't, Tags should be in the session already.
    if (isset($_POST['submit'])) {
        $_SESSION['tags'] = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
    }

    // Search Them
    $orgs = Organization::withTags($_SESSION['tags']);

    // Generate Tag List
    $tag_list = [];
    foreach ($_SESSION['tags'] as $tid) {
        $tag_list[] = getTagName((int)$tid);
    }

    // Display the Results
    echo "<!-- Search Form - Tags Only -->
                <div id='fh5co-contact' class='animate-box'>
                    <div class='container'>
                        <div class='row'>
                            <div class='col-md-12'>
                                <h2 class='section-title'>Search Results</h2>
                                <p>Below, you can find your search results. Organizations that matched the most number of
                                tagged interests are listed first. To start a new search, <a href='index.php?p=search&s=new'>click here</a>!</p>
                                <p>The tags you selected were: " . implode(", ", $tag_list) . "</p>
                            </div>
                        </div>";
    // Loop Through Results
    if (empty($orgs)) {
        echo "<div class='row'>
                    <div class='col-md-12'>
                        <p><strong>We're Sorry.</strong> No organizations matched your search tags. Please encourage organizations 
                        to <a href='index.php?p=add'>submit their information</a> to our database for better search results. 
                        To view the entire directory of groups, <a href=\"index.php?p=directory\">click here</a>.</p>
                    </div>
                  </div>";
    } else {
        /** @var Organization $o */
        $count = 1;
        $total = count($orgs);
        foreach ($orgs as $o) {
            // Web and Social Links
            $social = "";
            if (!empty($o->getWebsite())) {
                $social .= "<a href='" . $o->getWebsite() . "' target='_blank' title='Website'><i class='fa fa-globe' style='margin:0 4px;'></i></a>";
            }
            if (!empty($o->getFacebook())) {
                $social .= "<a href='" . $o->getFacebook() . "' target='_blank' title='Facebook'><i class='fa fa-facebook-official' style='margin:0 4px;'></i></a>";
            }
            if (!empty($o->getTwitter())) {
                $social .= "<a href='" . $o->getTwitter() . "' target='_blank' title='Twitter'><i class='fa fa-twitter' style='margin:0 4px;'></i></a>";
            }
            if (!empty($o->getYoutube())) {
                $social .= "<a href='" . $o->getYoutube() . "' target='_blank' title='YouTube'><i class='fa fa-youtube' style='margin:0 4px;'></i></a>";
            }
            if (!empty($o->getMeetup())) {
                $social .= "<a href='" . $o->getMeetup() . "' target='_blank' title='MeetUp'><i class='fa fa-meetup' style='margin:0 4px;'></i></a>";
            }

            // Email Link
            $email = (empty($o->getContactEmail())) ? "" : "<a href='mailto:" . $o->getContactEmail() . "'><i class='fa fa-envelope' title='Email'></i></a>";

            // Does the Group have an Address
            $address = "";
            if (!empty($o->getAddressCity()) || !empty($o->getAddressStreet())) {
                $address = "<tr>
                                  <td colspan='2'><strong>Address</strong>:";

                if (!empty($o->getAddressStreet())) {
                    $address .= " " . $o->getAddressStreet();
                }
                if (!empty($o->getAddressCity())) {
                    $address .= " " . $o->getAddressCity() . ", IN";
                }
                if (!empty($o->getAddressZipcode())) {
                    $address .= " " . $o->getAddressZipcode();
                }

                $address .= "</td>
                                </tr>";
            }

            // Was a County Listed?
            $county = "&nbsp;";
            if ($o->getAddressCounty() != "None Specified") {
                $county = "<strong>County</strong>: " . $o->getAddressCounty();
            }

            if (($count % 2) == 1) {
                echo "<div class='row' style='margin-bottom: 20px;'>";
            }
            echo "   <div class='col-md-6'>
                            <table style='width:90%;'>
                                <thead>
                                    <tr style='border-bottom: 3px solid #ff5722'>
                                        <th colspan='2'><h4 style='font-weight:bold;margin-bottom:5px;float:left;''>" . $o->getName() . "</h4><div style='float:right;vertical-align:text-top;'>$social</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan='2'>" . $o->getDescription() . "</td>
                                    </tr>
                                    <tr>
                                        <td style='width:50%;'><strong> Contact</strong>: " . $o->getContactFirstName() . " " . $o->getContactLastName() . " $email</td>
                                        <td style='width:50%;'>$county</td>
                                    </tr>
                                    $address
                                </tbody>
                            </table>
                        </div>";
            $total--;
            if (($count % 2 == 0) || $total == 0) {
                echo "</div>";
            }
            $count++;
        }
    }

    echo "</div>
           </div>";
} else {

    ?>
    <!-- Search Form - Tags Only -->
    <div id="fh5co-contact" class="animate-box">
        <div class="container">
            <form method="post" action="index.php?p=search">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="section-title">Become an Active Participant!</h3>
                        <p>Please select the tags / interests that appeal to you, and we will provide a list of local
                            centered around those interests. From there, you can find a group that suits your passsions
                            and begin to turn that passion into action. To view the entire directory of groups,
                            <a href="index.php?p=directory">click here</a>.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <select name="tags[]" id="tags" data-placeholder="Select Tags to Search..."
                                    class="form-control" title="Tags" multiple="multiple">
                                <?php

                                foreach (getTags() as $tag) {
                                    echo "<option value='$tag->tag_id'>$tag->name</option>";
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="submit" name="submit" value="Find Your Groups" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Search Form -->

    <?php

}