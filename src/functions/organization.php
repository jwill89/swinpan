<?php

/**
 * Created by PhpStorm.
 * User: James
 * Date: 5/12/2017
 * Time: 9:31 PM
 */
class Organization
{
    // Basic Information
    protected $org_id;
    protected $active;
    protected $name;
    protected $description;
    protected $website;

    // Social Media
    protected $facebook;
    protected $twitter;
    protected $youtube;
    protected $meetup;

    // Address Information
    protected $address_street;
    protected $address_city;
    protected $address_zipcode;
    protected $address_county;

    // Contact Information
    protected $contact_first_name;
    protected $contact_last_name;
    protected $contact_phone;
    protected $contact_email;

    /**
     * Organization constructor.
     * @param array|null $props
     */
    public function __construct(array $props = null)
    {
        // Is the property array null or empty?
        if (!empty($props) && is_array($props)) {

            // Loop Through Property Array
            foreach ($props as $key => $value) {

                // Does the Property Exist (And Not Array)
                if (property_exists("Organization",$key) && !is_array($value)) {

                    // Set the Property
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Filter Add Group Input
     * @return array|null
     */
    public static function filter(): ?array
    {
        $args = array(
            "name"                  => FILTER_SANITIZE_STRING,
            "description"           => FILTER_SANITIZE_STRING,
            "website"               => FILTER_SANITIZE_URL,
            "facebook"              => FILTER_SANITIZE_URL,
            "twitter"               => FILTER_SANITIZE_URL,
            "youtube"               => FILTER_SANITIZE_URL,
            "meetup"                => FILTER_SANITIZE_URL,
            "address_street"        => FILTER_SANITIZE_STRING,
            "address_city"          => FILTER_SANITIZE_STRING,
            "address_zipcode"       => FILTER_SANITIZE_STRING,
            "address_county"        => FILTER_SANITIZE_STRING,
            "contact_first_name"    => FILTER_SANITIZE_STRING,
            "contact_last_name"     => FILTER_SANITIZE_STRING,
            "contact_phone"         => FILTER_SANITIZE_STRING,
            "contact_email"         => FILTER_SANITIZE_EMAIL,
            "tags"                  => array('filter'   => FILTER_SANITIZE_NUMBER_INT,
                                             'flags'    => FILTER_REQUIRE_ARRAY)
        );

        $post_data = filter_input_array(INPUT_POST, $args);

        foreach ($post_data as $key => $value) {
            if ($args[$key] == FILTER_SANITIZE_NUMBER_INT) {
                $post_data[$key] = (int)$value;
            }
        }

        return $post_data;
    }

    /**
     * Get All Organizations
     * @return array|null
     */
    public static function all(): ?array
    {
        $db = DB::getInstance();
        $sth = $db->query("SELECT * FROM organizations");
        $org_list = $sth->fetchAll(PDO::FETCH_CLASS, 'Organization');

        return $org_list;
    }

    /**
     * Get Active Organizations
     * @return array|null
     */
    public static function active(): ?array
    {
        $db = DB::getInstance();
        $stmt = $db->query("SELECT * FROM organizations WHERE active = 1", PDO::FETCH_CLASS, 'Organization');
        $active = $stmt->fetchAll();

        return $active;
    }

    /**
     * Get Active Organizations With Tags
     * @param array $tags
     * @return array|null
     */
    public static function withTags(array $tags): ?array
    {
        $db = DB::getInstance();
        $tag_list = "(" . implode(", ", $tags) . ")";
        $stmt = $db->query("SELECT org_id FROM organization_tags WHERE tag_id IN $tag_list GROUP BY org_id ORDER BY COUNT(*) DESC", PDO::FETCH_OBJ);
        $list = $stmt->fetchAll();

        $org_list = [];

        foreach ($list as $org) {
            // Define New Org
            $org = self::find((int)$org->org_id);

            // If Not False (meaning if there were results)
            if ($org) {
                $org_list[] = $org;
            }
        }

        return $org_list;
    }

    /**
     * Get Individual Organization (Active Only)
     * @param int $org_id
     * @return null|Organization
     */
    public static function find(int $org_id): ?Organization
    {
        $db = DB::getInstance();
        $sth = $db->prepare("SELECT * FROM organizations WHERE org_id = :org_id AND active = 1");
        $sth->execute(array('org_id' => $org_id));
        $org = $sth->fetchObject('Organization');

        if ($org) {
            return $org;
        } else {
            return null;
        }
    }

    /**
     * Returns list of tags for organizations.
     * @return string
     */
    public function getTags(): string
    {
        $db = DB::getInstance();
        $result = $db->query("SELECT tag_id FROM organization_tags WHERE org_id = $this->org_id", PDO::FETCH_OBJ)->fetchAll();

        $tags = [];

        foreach ($result as $r) {
            $tags[] = getTagName((int)$r->tag_id);
        }

        if (!empty($tags)) {
            return implode(", ", $tags);
        } else {
            return "None";
        }
    }

    /**
     * Returns the primary key of the Organization class.
     * @return string
     */
    public function getPrimaryKeyName(): string
    {
        return 'org_id';
    }

    /**
     * @return int
     */
    public function getPrimaryKeyValue(): int
    {
        return (int)$this->org_id;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return "organizations";
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        // Properties
        $properties = get_object_vars($this);
        unset($properties['org_id']);

        return $properties;

    }

    /**
     * @return int|null
     */
    public function getOrgId(): ?int
    {
        return $this->org_id;
    }

    /**
     * @param int $org_id
     */
    public function setOrgId(int $org_id)
    {
        $this->org_id = $org_id;
    }

    /**
     * @return bool
     */
    public function getActive(): bool
    {
        if ($this->active == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active)
    {
        $this->active = ($active) ? 1 : 0;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website = null)
    {
        $this->website = $website;
    }

    /**
     * @return null|string
     */
    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    /**
     * @param string|null $facebook
     */
    public function setFacebook(string $facebook = null)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return null|string
     */
    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    /**
     * @param string|null $twitter
     */
    public function setTwitter(string $twitter = null)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return null|string
     */
    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    /**
     * @param string|null $youtube
     */
    public function setYoutube(string $youtube = null)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return null|string
     */
    public function getMeetup(): ?string
    {
        return $this->meetup;
    }

    /**
     * @param string|null $meetup
     */
    public function setMeetup(string $meetup = null)
    {
        $this->meetup = $meetup;
    }

    /**
     * @return null|string
     */
    public function getAddressStreet(): ?string
    {
        return $this->address_street;
    }

    /**
     * @param string|null $address_street
     */
    public function setAddressStreet(string $address_street = null)
    {
        $this->address_street = $address_street;
    }

    /**
     * @return null|string
     */
    public function getAddressCity(): ?string
    {
        return $this->address_city;
    }

    /**
     * @param string|null $address_city
     */
    public function setAddressCity(string $address_city = null)
    {
        $this->address_city = $address_city;
    }

    /**
     * @return string|null
     */
    public function getAddressZipcode(): ?string
    {
        return $this->address_zipcode;
    }

    /**
     * @param string|null $address_zipcode
     */
    public function setAddressZipcode(string $address_zipcode = null)
    {
        $this->address_zipcode = $address_zipcode;
    }

    /**
     * @return null|string
     */
    public function getAddressCounty(): ?string
    {
        if (!empty($this->address_county)) {
            return $this->address_county;
        } else {
            return "None Specified";
        }
    }

    /**
     * @param string|null $address_county
     */
    public function setAddressCounty(string $address_county = null)
    {
        $this->address_county = $address_county;
    }

    /**
     * @return null|string
     */
    public function getContactFirstName(): ?string
    {
        return $this->contact_first_name;
    }

    /**
     * @param string $contact_first_name
     */
    public function setContactFirstName(string $contact_first_name)
    {
        $this->contact_first_name = $contact_first_name;
    }

    /**
     * @return null|string
     */
    public function getContactLastName(): ?string
    {
        return $this->contact_last_name;
    }

    /**
     * @param string $contact_last_name
     */
    public function setContactLastName(string $contact_last_name)
    {
        $this->contact_last_name = $contact_last_name;
    }

    /**
     * @return null|string
     */
    public function getContactPhone(): ?string
    {
        return $this->contact_phone;
    }

    /**
     * @param string|null $contact_phone
     */
    public function setContactPhone(string $contact_phone = null)
    {
        $this->contact_phone = $contact_phone;
    }

    /**
     * @return null|string
     */
    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }

    /**
     * @param string|null $contact_email
     */
    public function setContactEmail(string $contact_email = null)
    {
        $this->contact_email = $contact_email;
    }

}

/**
 * @param int $org_id
 * @param array $tags
 * @return bool
 */
function addOrgTags(int $org_id, array $tags) {

    // Get the DB
    $db = DB::getInstance();

    // Create the Values List
    $values = [];

    // Loop Through Values and Add to Array
    foreach ($tags as $tag) {
        $values[] = "($org_id, $tag)";
    }

    // Setup the SQL
    $sql = "INSERT INTO organization_tags (org_id, tag_id) VALUES " . implode(",", $values);

    // If Successful, Return True else False
    if ($db->query($sql)) {
        return true;
    } else {
        return false;
    }
}