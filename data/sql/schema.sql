CREATE TABLE alliance (id BIGINT, name text NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE buildings (id BIGINT AUTO_INCREMENT, type text NOT NULL, size_x BIGINT NOT NULL, size_y BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE building_levels (building_id BIGINT, level BIGINT, time BIGINT, requirements LONGTEXT, stats LONGTEXT, PRIMARY KEY(building_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE campaigns (id BIGINT, name text, unlock_level BIGINT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE campaigns_stages (id BIGINT, campaign_id BIGINT NOT NULL, name text, attacker_level BIGINT, attacker_boost FLOAT(18, 2), unit_level BIGINT, baseline_xp BIGINT, player_unlock_level BIGINT, INDEX campaign_id_idx (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE campaigns_stages_units (id BIGINT AUTO_INCREMENT, stage_id BIGINT NOT NULL, type text, quantity BIGINT, x BIGINT, y BIGINT, time BIGINT, INDEX stage_id_idx (stage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE chat (id BIGINT AUTO_INCREMENT, room text, player_id BIGINT, message text, user_card LONGTEXT, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE defense (id BIGINT AUTO_INCREMENT, type text NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE defense_levels (defense_id BIGINT, level BIGINT, time BIGINT, requirements LONGTEXT, stats LONGTEXT, PRIMARY KEY(defense_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE equipment (id BIGINT AUTO_INCREMENT, type text NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE equipment_level (equipment_id BIGINT, level BIGINT, tier BIGINT, time BIGINT, upgrade_chance BIGINT, requirements LONGTEXT, stats LONGTEXT, tags LONGTEXT, PRIMARY KEY(equipment_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE force_leaderboard (tournament_id BIGINT, timestamp BIGINT, rank BIGINT, user_id BIGINT, user_name text, power BIGINT, PRIMARY KEY(tournament_id, timestamp, rank)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE force_tournament (id BIGINT, dates text, sector BIGINT, type text, end_at BIGINT, daily_prizing LONGTEXT, bout_prizing LONGTEXT, challenge_prizing LONGTEXT, active_calculations LONGTEXT, value_adjustments LONGTEXT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE generals (id BIGINT AUTO_INCREMENT, type text, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE general_levels (general_id BIGINT, level BIGINT, requirements LONGTEXT, stats LONGTEXT, skills LONGTEXT, PRIMARY KEY(general_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE home (id BIGINT, name text, damage_protection datetime, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE items (id BIGINT, type text NOT NULL, permanent TINYINT(1) DEFAULT '0' NOT NULL, boost_amount BIGINT, boost_percentage BIGINT, boost_type text, resource_amount BIGINT, resource_type text, contents LONGTEXT, tags LONGTEXT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE map (id BIGINT, sector BIGINT, width BIGINT, height BIGINT, chunk_size BIGINT, outpost_levels LONGTEXT, upgrade_costs LONGTEXT, max_territory_limit BIGINT, type text, active TINYINT(1), maximum_node_level BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE map_node (id BIGINT, map_id BIGINT NOT NULL, x BIGINT NOT NULL, y BIGINT NOT NULL, owner text, owner_id BIGINT, collection text, collection_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX map_id_idx (map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE map_rewards (id BIGINT AUTO_INCREMENT, value BIGINT, rewards LONGTEXT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE meltdown (id BIGINT AUTO_INCREMENT, value text, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE player (id BIGINT, name text, level BIGINT, mainbase_id BIGINT, colonies LONGTEXT, alliance_id BIGINT, created_at datetime, login_at datetime, xp BIGINT, sp BIGINT, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE proxy (id BIGINT AUTO_INCREMENT, type text, params LONGTEXT, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE research (id BIGINT AUTO_INCREMENT, type text NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE research_levels (research_id BIGINT, level BIGINT, time BIGINT, requirements LONGTEXT, PRIMARY KEY(research_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE skills (id BIGINT AUTO_INCREMENT, type text, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE skill_levels (skill_id BIGINT, level BIGINT, requirements LONGTEXT, stats LONGTEXT, PRIMARY KEY(skill_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE units (id BIGINT AUTO_INCREMENT, type text, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
CREATE TABLE unit_levels (unit_id BIGINT, level BIGINT, time BIGINT, requirements LONGTEXT, stats LONGTEXT, PRIMARY KEY(unit_id, level)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;
ALTER TABLE building_levels ADD CONSTRAINT building_levels_building_id_buildings_id FOREIGN KEY (building_id) REFERENCES buildings(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE campaigns_stages ADD CONSTRAINT campaigns_stages_campaign_id_campaigns_id FOREIGN KEY (campaign_id) REFERENCES campaigns(id);
ALTER TABLE campaigns_stages_units ADD CONSTRAINT campaigns_stages_units_stage_id_campaigns_stages_id FOREIGN KEY (stage_id) REFERENCES campaigns_stages(id);
ALTER TABLE defense_levels ADD CONSTRAINT defense_levels_defense_id_defense_id FOREIGN KEY (defense_id) REFERENCES defense(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE equipment_level ADD CONSTRAINT equipment_level_equipment_id_equipment_id FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE force_leaderboard ADD CONSTRAINT force_leaderboard_tournament_id_force_tournament_id FOREIGN KEY (tournament_id) REFERENCES force_tournament(id);
ALTER TABLE general_levels ADD CONSTRAINT general_levels_general_id_generals_id FOREIGN KEY (general_id) REFERENCES generals(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE map_node ADD CONSTRAINT map_node_map_id_map_id FOREIGN KEY (map_id) REFERENCES map(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE research_levels ADD CONSTRAINT research_levels_research_id_research_id FOREIGN KEY (research_id) REFERENCES research(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE skill_levels ADD CONSTRAINT skill_levels_skill_id_skills_id FOREIGN KEY (skill_id) REFERENCES skills(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE unit_levels ADD CONSTRAINT unit_levels_unit_id_units_id FOREIGN KEY (unit_id) REFERENCES units(id) ON UPDATE CASCADE ON DELETE CASCADE;
