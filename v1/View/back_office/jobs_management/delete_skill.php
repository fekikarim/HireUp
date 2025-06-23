<?php 

require_once __DIR__ . '/../../../Controller/wanted_skill_con.php';

$skill = new WantedSkillController();

if (isset($_GET["id"])) {
    $skill->deleteSkill($_GET["id"]);
}


?>