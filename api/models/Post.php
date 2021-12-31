<?php

	class Post
	{
		//DB stuff
		private $conn;
		private $table = 'tb_person';

		//Post Properties


		//Constructor with DB
		public function __construct($db)
		{
			$this->conn = $db;
		}

		//Get Posts
		public function read()
		{
			//Create query
			$query = 'SELECT 
					p.personal_id,
					p.case_id,
					p.f_name_arm,
					p.l_name_arm,
					p.m_name_arm,
       				p.f_name_eng,
					p.l_name_eng,
					p.m_name_eng,
					p.b_day,
					p.b_month,
					p.b_year,
					p.citizenship, 
					p.previous_residence, 
					p.citizen_adr,
					p.residence_adr,
					p.departure_from_citizen AS citizen_leaving_date,
					p.departure_from_residence AS residence_leaving_date,
					p.arrival_date,
					p.doc_num,
					p.etnicity, 
					p.religion, 
					p.invalid, 
					p.pregnant, 
					p.seriously_ill, 
					p.trafficking_victim, 
					p.violence_victim, 
					p.illegal_border, 
					p.transfer_moj, 
					p.deport_prescurator, 
					p.prison, 
					p.role, 
					p.image,  
					p.pnum, 
					p.doc_type, 
					p.document_num, 
					p.doc_issued_date, 
					p.doc_valid,
					p.doc_issued_by, 
					p.bpr_community, 
					p.bpr_bnakavayr, 
					p.bpr_street, 
					p.bpr_house, 
					p.bpr_aprt,
       				c.country_arm AS citizenship_country,
       				r.country_arm AS residence_country,
       				e.etnic_eng AS etnicity,
       				g.religion_arm,
					rl.der,
					mz.ADM1_ARM AS address_marz,
					com.ADM3_ARM AS address_community,
					setl.ADM4_ARM AS address_settlement,
					cs.RA_street AS address_street,
					cs.RA_building AS address_building,
					cs.RA_apartment AS address_appartment
				FROM 
					tb_person p 
				LEFT JOIN tb_country  c ON p.citizenship = c.country_id 
				LEFT JOIN tb_country  r ON p.previous_residence = r.country_id 
				LEFT JOIN tb_etnics   e ON p.etnicity = e.etnic_id  
				LEFT JOIN tb_religions g ON p.religion = g.religion_id   
				LEFT JOIN tb_role rl ON p.role = rl.role_id    
				LEFT JOIN tb_case cs ON p.case_id = cs.case_id     
				LEFT JOIN tb_marz mz ON cs.RA_marz = mz.marz_id      
				LEFT JOIN tb_arm_com com ON cs.RA_community = com.community_id       
				LEFT JOIN tb_settlement setl ON cs.RA_settlement = setl.settlement_id  
					ORDER BY p.personal_id DESC';

			//Prepare statement
			$stmt = $this->conn->prepare($query);

			//Execute query
			$stmt->execute();

			return $stmt;
		}


		//Get single post
		public function read_single($keys,$values)
		{
			//Single person post query
			$query = 'SELECT 
					p.personal_id,
					p.case_id,
					p.f_name_arm,
					p.l_name_arm,
					p.m_name_arm,
       				p.f_name_eng,
					p.l_name_eng,
					p.m_name_eng,
					p.b_day,
					p.b_month,
					p.b_year,
					p.citizenship, 
					p.previous_residence, 
					p.citizen_adr,
					p.residence_adr,
					p.departure_from_citizen AS citizen_leaving_date,
					p.departure_from_residence AS residence_leaving_date,
					p.arrival_date,
					p.doc_num,
					p.etnicity, 
					p.religion, 
					p.invalid, 
					p.pregnant, 
					p.seriously_ill, 
					p.trafficking_victim, 
					p.violence_victim, 
					p.illegal_border, 
					p.transfer_moj, 
					p.deport_prescurator, 
					p.prison, 
					p.role, 
					p.image,  
					p.pnum, 
					p.doc_type, 
					p.document_num, 
					p.doc_issued_date, 
					p.doc_valid,
					p.doc_issued_by, 
					p.bpr_community, 
					p.bpr_bnakavayr, 
					p.bpr_street, 
					p.bpr_house, 
					p.bpr_aprt,
       				c.country_arm AS citizenship_country,
       				r.country_arm AS residence_country,
       				e.etnic_eng AS etnicity,
       				g.religion_arm,
					rl.der,
					mz.ADM1_ARM AS address_marz,
					com.ADM3_ARM AS address_community,
					setl.ADM4_ARM AS address_settlement,
					cs.RA_street AS address_street,
					cs.RA_building AS address_building,
					cs.RA_apartment AS address_appartment
				FROM 
					tb_person p 
				LEFT JOIN tb_country  c ON p.citizenship = c.country_id 
				LEFT JOIN tb_country  r ON p.previous_residence = r.country_id 
				LEFT JOIN tb_etnics   e ON p.etnicity = e.etnic_id  
				LEFT JOIN tb_religions g ON p.religion = g.religion_id   
				LEFT JOIN tb_role rl ON p.role = rl.role_id    
				LEFT JOIN tb_case cs ON p.case_id = cs.case_id     
				LEFT JOIN tb_marz mz ON cs.RA_marz = mz.marz_id      
				LEFT JOIN tb_arm_com com ON cs.RA_community = com.community_id       
				LEFT JOIN tb_settlement setl ON cs.RA_settlement = setl.settlement_id        
				WHERE ';
			foreach ($keys as $key  ){
				$query .= " p.$key = ? AND";
			}
			$query=substr($query, 0, -3);
			//Prepare statement
			$stmt = $this->conn->prepare($query);

			//Execute query
			$stmt->execute($values);
			return $stmt;

		}
	}