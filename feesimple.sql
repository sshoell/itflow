DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `person_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` int(11) NULL,
  `last_name` varchar(255) NULL,
  `license_number` text DEFAULT NULL,
  `is_loan_officer` boolean NOT NULL DEFAULT false,
  `is_real_estate_agent` boolean NOT NULL DEFAULT false,
  `is_title_examiner` boolean NOT NULL DEFAULT false,
  `is_escrow_officer` boolean NOT NULL DEFAULT false,
  `is_Real_estate_broker` boolean NOT NULL DEFAULT false,
  `is_mortgate_broker` boolean NOT NULL DEFAULT false,
  `is_agency_manager` boolean NOT NULL DEFAULT false,
  `broker_id` int(11) NULL,
  `company_id` int(11) NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` int(11) NULL,
  `license_number` text DEFAULT NULL,
  `is_real_estate_company` boolean NOT NULL DEFAULT false,
  `is_mortgage_company` boolean NOT NULL DEFAULT false,
  `is_title_company` boolean NOT NULL DEFAULT false,
  `is_title_agency` boolean NOT NULL DEFAULT false,
  `is_lender` boolean NOT NULL DEFAULT false,
  `parent_company_id` int(11) NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `parties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parties` (
  `party_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_buyer` boolean NOT NULL DEFAULT false,
  `is_seller` boolean NOT NULL DEFAULT false,
  `is_buyer_agent` boolean NOT NULL DEFAULT false,
  `is_seller_agent` boolean NOT NULL DEFAULT false,
  `is_loan_officer` boolean NOT NULL DEFAULT false,
  `is_real_estate_agent` boolean NOT NULL DEFAULT false,
  `is_title_examiner` boolean NOT NULL DEFAULT false,
  `is_escrow_officer` boolean NOT NULL DEFAULT false,
  `is_Real_estate_broker` boolean NOT NULL DEFAULT false,
  `is_mortgate_broker` boolean NOT NULL DEFAULT false,
  `is_agency_manager` boolean NOT NULL DEFAULT false,
  `is_escrow_assistant` boolean NOT NULL DEFAULT false,
  `is_title_assistant` boolean NOT NULL DEFAULT false,
  `is_insurance_marketer` boolean NOT NULL DEFAULT false,
  `is_insurance_sales` boolean NOT NULL DEFAULT false,
  `is_notory` boolean NOT NULL DEFAULT false,
  `is_signatory` boolean NOT NULL DEFAULT false,
  `is_attorney` boolean NOT NULL DEFAULT false,
  `is_lienholder` boolean NOT NULL DEFAULT false,
  `is_title_insurer` boolean NOT NULL DEFAULT false,
  `is_referer` boolean NOT NULL DEFAULT false,
  `order_id` int(11) NOT NULL,
  `selector_field_person_company` int(11) NOT NULL,
  `selector_field_key` int(11) NOT NULL,
  PRIMARY KEY (`party_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_number` int(11) NOT NULL DEFAULT 1,
  `order_status` varchar(255) NOT NULL,  
  `order_type` varchar(255) NOT NULL,
  `sales_price` decimal(15,2) NULL DEFAULT 0.00,
  `tracking_location` varchar(255) NULL,
  `order_date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `order_updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `order_closed_at` datetime DEFAULT NULL,
  `settlement_escrow_fees` decimal(15,2) NULL DEFAULT 0.00,
  `bank` varchar(255) NULL,
  `file_balance` decimal(15,2) NULL DEFAULT 0.00,
  `closing_date` datetime DEFAULT NULL,
  `order_created_by_person_id` int(11) NULL,
  PRIMARY KEY (`account_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL AUTO_INCREMENT,
  `street_1` varchar(255) NOT NULL, 
  `street_2` varchar(255) NULL,  
  `street_3` varchar(255) NULL,
  `city` varchar(50) NOT NULL DEFAULT 'UT',
  `county` varchar(50) NULL,
  `county_code` varchar(12) NULL,
  `state` varchar(10) NOT NULL,  
  `postal_code` varchar(10) NULL,
  `is_property`  boolean NOT NULL DEFAULT false,
  `property_type` varchar(30) NULL,
  `legal_description` varchar(255) NULL,
  `parcel_number` varchar(50) NULL,
  `order_id` int(11) NULL,
  `person_id` int(11) NULL,
  `company_id` int(11) NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `policies` (
  `policy_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_issued` boolean NOT NULL DEFAULT false,
  `is_owners` boolean NOT NULL DEFAULT false,
  `is_lendors` boolean NOT NULL DEFAULT false,
  `policy_liablity` decimal(15,2) NULL DEFAULT 0.00,
  `premium_amount` decimal(15,2) NULL DEFAULT 0.00,
  `endorsements_amount` decimal(15,2) NULL DEFAULT 0.00,
  `is_premium_disbursed` boolean NOT NULL DEFAULT false,
  `premium_disbursement_date` date DEFAULT NULL,
  `loan_type` varchar(255) NULL,
  `policy_type` varchar(255) NULL,
  `policy_number` varchar(255) NULL,
  `policy_effective_date` varchar(255) NULL,
  `policy_issued_date` varchar(255) NULL,
  `underwriter_company_id` int(11) NULL,
  `order_id` int(11) NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;