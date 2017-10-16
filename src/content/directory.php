<?php

// Get Active organizations
$orgs = Organization::active();

// Display the Results
echo "<!-- Search Form - Tags Only -->
            <div id='fh5co-contact' class='animate-box'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-md-12'>
                            <h2 class='section-title'>Organization Directory</h2>
                            <p>Below is a directory listing of all groups who have submitted information to our database.
                            To search for groups based on your specific interests <a href='index.php?p=search&s=new'>click here</a>!</p>
                        </div>
                    </div>";
// Loop Through Results
if (empty($orgs)) {
    echo "<div class='row'>
                <div class='col-md-12'>
                    <p><strong>We're Sorry.</strong> No organizations have submitted their information yet. 
                    <a href='index.php?p=add'>Click here</a> to add your group's information!</p>
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
            $social .= "<a href='" . $o->getWebsite() . "' target='_blank' title='Website'><i class='fa fa-globe fa-lg' style='margin:0 4px;'></i></a>";
        }
        if (!empty($o->getFacebook())) {
            $social .= "<a href='" . $o->getFacebook() . "' target='_blank' title='Facebook'><i class='fa fa-facebook-official fa-lg' style='margin:0 4px;'></i></a>";
        }
        if (!empty($o->getTwitter())) {
            $social .= "<a href='" . $o->getTwitter() . "' target='_blank' title='Twitter'><i class='fa fa-twitter fa-lg' style='margin:0 4px;'></i></a>";
        }
        if (!empty($o->getYoutube())) {
            $social .= "<a href='" . $o->getYoutube() . "' target='_blank' title='YouTube'><i class='fa fa-youtube fa-lg' style='margin:0 4px;'></i></a>";
        }
        if (!empty($o->getMeetup())) {
            $social .= "<a href='" . $o->getMeetup() . "' target='_blank' title='MeetUp'><i class='fa fa-meetup fa-lg' style='margin:0 4px;'></i></a>";
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
            echo "<div class='row' style='margin-bottom: 50px;'>";
        }
        echo "   <div class='col-md-6'>
                            <table style='width:90%;'>
                                <thead>
                                    <tr style='border-bottom: 3px solid #ff5722'>
                                        <th colspan='2'><h4 style='font-weight:bold;margin-bottom:5px;float:left;'>" . $o->getName() . "</h4><div style='float:right;vertical-align:text-top;'>$social</div></th>
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