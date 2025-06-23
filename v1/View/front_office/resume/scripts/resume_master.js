


function loadResumeData() {
    resume_data = localStorage.getItem('resumeData-cv');
    resume_data_json = JSON.parse(resume_data);
    return resume_data_json;
}

function saveResumeData(resume_data) {
    localStorage.setItem('resumeData-cv', JSON.stringify(resume_data));
}

function initBasicInfos() {

    data = {
        profile_image: '',
        first_name: '',
        last_name: '',
        title: '',
        about_me: '',
        age: '',
        email: '',
        phone: '',
        address: '',
        skills: [],
        experiences: [],
        educations: [],
      };
    

    saveResumeData(data);
}

async function makeBasicInfos() {

    resume_data = loadResumeData();
    
    //base64String = await getBase64FromInput('resume_picture');
    full_name = document.getElementById('resume_name').value;
    first_name = full_name.split(' ')[0];
    last_name = full_name.split(' ')[1];
    title = document.getElementById('resume_job').value;
    about_me = document.getElementById('resume_about_me').value;
    age = '0';
    email = document.getElementById('email').value;
    phone = document.getElementById('resume_phone').value;
    address = document.getElementById('resume_adresse').value;

    
    resume_data.profile_image = 'base64String',
    resume_data.first_name = first_name,
    resume_data.last_name = last_name,
    resume_data.title = title,
    resume_data.about_me = about_me,
    resume_data.age = age,
    resume_data.email = email,
    resume_data.phone = phone,
    resume_data.address = address,

    console.log('resume_data');
    console.log(resume_data);
    
    saveResumeData(resume_data);
}

function clearBasic() {
    document.getElementById('resume_picture').value = '';
    document.getElementById('resume_name').value = '';
    document.getElementById('resume_job').value = '';
    document.getElementById('resume_about_me').value = '';
    document.getElementById('resume_phone').value = '';
    document.getElementById('resume_adresse').value = '';
    document.getElementById('email').value = '';

    document.getElementById('name_error').innerHTML = '';
    document.getElementById('phone_error').innerHTML = '';
    document.getElementById('email_error').innerHTML = '';
    document.getElementById('job_error').innerHTML = '';
    document.getElementById('adresse_error').innerHTML = '';
    document.getElementById('pic_error').innerHTML = '';
    document.getElementById('aboutMe_error').innerHTML = '';

}

//skills
function makeSkill(id, skill, skill_progress) {
    return {id: id, name: skill, progress: skill_progress};
}

function clearSkill() {
    document.getElementById('resume_skills').value = '';
    document.getElementById('resume_progress').value = '';

    progressBar = document.getElementById('progress-bar');
    progressBar.style.width = 0 + '%';

    document.getElementById('skill_error').innerHTML = '';
    document.getElementById('progress_error').innerHTML = '';
}

function loadSkill(id) {
    resume_data = loadResumeData();
    for(i=0; i<resume_data.skills.length; i++) {
        if (resume_data.skills[i].id == id) {
            return resume_data.skills[i];
        }
    }

    return null;
        

}

function addSkill() {
    resume_data = loadResumeData();
    id = (resume_data.skills.length).toString();
    skill = document.getElementById('resume_skills').value;
    skill_progress = document.getElementById('resume_progress').value;
    skill = makeSkill(id, skill, skill_progress);
    resume_data.skills.push(skill);
    console.log("pusshed skill");
    console.log(resume_data.skills);
    saveResumeData(resume_data);
    clearSkill();
    loadSkillsToDropDown(resume_data.skills);
    //generateSkills(JSON.stringify(resume_data.skills), 'skills-output');
}

function removeSkill(id) {
    resume_data = loadResumeData();
    resume_data.skills = resume_data.skills.filter(skill => skill.id != id);
    console.log("removed skill");
    console.log(resume_data.skills);
    saveResumeData(resume_data);
    loadSkillsToDropDown(resume_data.skills);
}

function editSkill(id) {
    resume_data = loadResumeData();
    //skill = makeSkill(id);
    //resume_data.skills[id] = skill;
    showModal(id);
    saveResumeData(resume_data);
}

function updateSkill(id, skill) {
    resume_data = loadResumeData();
    for(i=0; i<resume_data.skills.length; i++) {
        if (resume_data.skills[i].id == id) {
            resume_data.skills[i] = skill;
            return resume_data;
        }
    }

    return resume_data;
        

}

//work
function makeWork(id, job_exp, company, start_date, end_date, description) {
    return {id: id, job_exp: job_exp, company: company, start_date: start_date, end_date: end_date, description: description};
}

function clearWork() {
    document.getElementById('job_exp').value = '';
    document.getElementById('exp_company').value = '';
    document.getElementById('exp_start').value = '';
    document.getElementById('exp_end').value = '';
    document.getElementById('exp_description').value = '';

    document.getElementById('jobName_error').innerHTML = '';
    document.getElementById('company_error').innerHTML = '';
    document.getElementById('work_start_error').innerHTML = '';
    document.getElementById('work_end_error').innerHTML = '';
    document.getElementById('work_desc_error').innerHTML = '';
}

function loadWork(id) {
    resume_data = loadResumeData();
    for(i=0; i<resume_data.experiences.length; i++) {
        if (resume_data.experiences[i].id == id) {
            return resume_data.experiences[i];
        }
    }

    return null;
}

function addWork() {
    resume_data = loadResumeData();
    id = (resume_data.experiences.length).toString();

    job_exp = document.getElementById('job_exp').value;
    company = document.getElementById('exp_company').value;
    start_date = document.getElementById('exp_start').value;
    end_date = document.getElementById('exp_end').value;
    desc = document.getElementById('exp_description').value;
    
    experiences = makeWork(id, job_exp, company, start_date, end_date, desc);

    resume_data.experiences.push(experiences);
    console.log("pusshed experiences");
    console.log(resume_data.experiences);
    saveResumeData(resume_data);
    clearWork();
    loadWorksToDropDown(resume_data.experiences);
    //generateSkills(JSON.stringify(resume_data.skills), 'skills-output');
}

function removeWork(id) {
    resume_data = loadResumeData();
    resume_data.experiences = resume_data.experiences.filter(experience => experience.id != id);
    saveResumeData(resume_data);
    loadWorksToDropDown(resume_data.experiences);
}

function editWork(id) {
    resume_data = loadResumeData();
    showModalWork(id);
    saveResumeData(resume_data);
}

function updateWork(id, work) {
    resume_data = loadResumeData();
    for(i=0; i<resume_data.experiences.length; i++) {
        if (resume_data.experiences[i].id == id) {
            resume_data.experiences[i] = work;
            return resume_data;
        }
    }

    return resume_data;
}

//education
function makeEducation(id, inst, start_date, end_date, degree, description) {
    return {id: id, inst: inst, start_date: start_date, end_date: end_date, degree: degree, description: description};
}

function clearEducation() {
    document.getElementById('edu_institution').value = '';
    document.getElementById('edu_start').value = '';
    document.getElementById('edu_end').value = '';
    document.getElementById('edu_degree').value = '';
    document.getElementById('edu_description').value = '';

    document.getElementById('institut_error').innerHTML = '';
    document.getElementById('degree_error').innerHTML = '';
    document.getElementById('edu_start_error').innerHTML = '';
    document.getElementById('edu_end_error').innerHTML = '';
    document.getElementById('edu_desc_error').innerHTML = '';

}

function loadEducation(id){

    resume_data = loadResumeData();
    for(i=0; i<resume_data.educations.length; i++) {
        if (resume_data.educations[i].id == id) {
            return resume_data.educations[i];
        }
    }

    return null;

}

function addEducation() {
    resume_data = loadResumeData();
    id = (resume_data.educations.length).toString();

    eduIns = document.getElementById('edu_institution').value;
    eduStart = document.getElementById('edu_start').value;
    eduEnd = document.getElementById('edu_end').value;
    eduDegree = document.getElementById('edu_degree').value;
    eduDesc = document.getElementById('edu_description').value;
    
    education = makeEducation(id, eduIns, eduStart, eduEnd, eduDegree, eduDesc);

    resume_data.educations.push(education);
    console.log("pusshed educations");
    console.log(resume_data.educations);
    saveResumeData(resume_data);
    clearEducation();
    loadEducationToDropDown(resume_data.educations);
    //generateSkills(JSON.stringify(resume_data.skills), 'skills-output');
}

function removeEducation(id) {
    resume_data = loadResumeData();
    resume_data.educations = resume_data.educations.filter(education => education.id != id);
    saveResumeData(resume_data);
    loadEducationToDropDown(resume_data.educations);
}

function editEducation(id) {
    resume_data = loadResumeData();
    showModalEducation(id);
    saveResumeData(resume_data);
}

function updateEducation(id, education) {

    resume_data = loadResumeData();
    for(i=0; i<resume_data.educations.length; i++) {
        if (resume_data.educations[i].id == id) {
            resume_data.educations[i] = education;

            return resume_data;
        }
    }

    return resume_data;

}

// generators
function loadSkillsToDropDown(skills) {
    dropdownMenu_s = document.getElementById('dropdownMenu');

    //clear the dropdown menu
    document.getElementById('dropdownMenu').innerHTML = '';

    if (skills.length < 1) {
        const item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = `
                                        <span>You haven't added anything.</span>
                                    `;
        dropdownMenu_s.appendChild(item);
    }

    skills.forEach((skill) => {
        const item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = `
                                        <span>${skill.name}</span>
                                        <div>
                                            <a href="javascript:void(0);" onclick="editSkill('${skill.id}')"><i class="fa fa-edit text-primary"></i></a>
                                            <a href="javascript:void(0);" onclick="removeSkill('${skill.id}')"><i class="fa fa-x text-danger"></i></a>
                                        </div>
                                    `;
        dropdownMenu_s.appendChild(item);
    });
}

function loadWorksToDropDown(works) {
    dropdownMenu_w = document.getElementById('dropdownMenu2');

    //clear the dropdown menu
    document.getElementById('dropdownMenu2').innerHTML = '';

    if (works.length < 1) {
        const item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = `
                                        <span>You haven't added anything.</span>
                                    `;
        dropdownMenu_w.appendChild(item);
    }

    works.forEach((work) => {
        const item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = `
                                        <span>${work.job_exp}</span>
                                        <div>
                                            <a href="javascript:void(0);" onclick="editWork('${work.id}')"><i class="fa fa-edit text-primary"></i></a>
                                            <a href="javascript:void(0);" onclick="removeWork('${work.id}')"><i class="fa fa-x text-danger"></i></a>
                                        </div>
                                    `;
        dropdownMenu_w.appendChild(item);
    });
}

function loadEducationToDropDown(educs) {
    console.log("loadEducationToDropDown");
    console.log(educs);
    dropdownMenu_e = document.getElementById('dropdownMenu3');

    //clear the dropdown menu
    document.getElementById('dropdownMenu3').innerHTML = '';

    if (educs.length < 1) {
        const item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = `
                                        <span>You haven't added anything.</span>
                                    `;
        dropdownMenu_e.appendChild(item);
    }

    educs.forEach((educ) => {
        console.log("educ");
        console.log(educ);
        const item = document.createElement('div');
        item.className = 'dropdown-item';
        item.innerHTML = `
                                        <span>${educ.inst}</span>
                                        <div>
                                            <a href="javascript:void(0);" onclick="editEducation('${educ.id}')"><i class="fa fa-edit text-primary"></i></a>
                                            <a href="javascript:void(0);" onclick="removeEducation('${educ.id}')"><i class="fa fa-x text-danger"></i></a>
                                        </div>
                                    `;
        dropdownMenu_e.appendChild(item);
    });
}



function makeDataResume() {
    makeBasicInfos();
    console.log(loadResumeData())
}

//modale

//skill
function loadSkillToModal(skill) {
    console.log("loadSkillToModal");
    console.log(skill);
    document.getElementById('skillIdEd').value = skill.id;
    document.getElementById('skillNameEd').value = skill.name;
    document.getElementById('skillProgressEd').value = skill.progress;
}


function showModal(id) {
    skill = loadSkill(id);
    loadSkillToModal(skill);
    const modal = document.getElementById('editSkillModal');
    modal.classList.add('show');
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    document.body.appendChild(backdrop);
    updateProgressBar2();
}

function closeModal() {
    const modal = document.getElementById('editSkillModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
    }
}

function loadSkillFromModal() {
    id = document.getElementById('skillIdEd').value,
    name_sk = document.getElementById('skillNameEd').value,
    progress = document.getElementById('skillProgressEd').value
    skill = makeSkill(id, name_sk, progress);
    data = updateSkill(id, skill);
    saveResumeData(data);
    loadSkillsToDropDown(data.skills);
    closeModal();
}


//work
function loadWorkToModal(work){
    document.getElementById('job_id_ed').value = work.id;
    document.getElementById('job_exp_ed').value = work.job_exp;
    document.getElementById('exp_company_ed').value = work.company;
    document.getElementById('exp_start_ed').value = work.start_date;
    document.getElementById('exp_end_ed').value = work.end_date;
    document.getElementById('exp_description_ed').value = work.description;
}

function showModalWork() {
    work = loadWork(id);
    loadWorkToModal(work);
    const modal = document.getElementById('editWorkModal');
    modal.classList.add('show');
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    document.body.appendChild(backdrop);
}

function closeModalWork() {
    const modal = document.getElementById('editWorkModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
    }
}

function loadWorkFromModal() {
    id = document.getElementById('job_id_ed').value;
    job_exp = document.getElementById('job_exp_ed').value;
    company = document.getElementById('exp_company_ed').value;
    start_date = document.getElementById('exp_start_ed').value;
    end_date = document.getElementById('exp_end_ed').value;
    description = document.getElementById('exp_description_ed').value;
    work = makeWork(id, job_exp, company, start_date, end_date, description);
    data = updateWork(id, work);
    saveResumeData(data);
    loadWorksToDropDown(data.experiences);
    closeModalWork();
}


//education
function loadEduToModal(educ){
    document.getElementById('edit_edu_id').value = educ.id;
    document.getElementById('edit_edu_institution').value = educ.inst;
    document.getElementById('edit_edu_start').value = educ.start_date;
    document.getElementById('edit_edu_end').value = educ.end_date;
    document.getElementById('edit_edu_degree').value = educ.degree;
    document.getElementById('edit_edu_description').value = educ.description;
}

function showModalEducation() {
    education = loadEducation(id);
    loadEduToModal(education);
    const modal = document.getElementById('editEduModal');
    modal.classList.add('show');
    modal.style.display = 'block';
    document.body.classList.add('modal-open');
    const backdrop = document.createElement('div');
    backdrop.className = 'modal-backdrop fade show';
    document.body.appendChild(backdrop);
}

function closeModalEducation() {
    const modal = document.getElementById('editEduModal');
    modal.classList.remove('show');
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    const backdrop = document.querySelector('.modal-backdrop');
    if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
    }
}

function loadEducationFromModal() {
    id = document.getElementById('edit_edu_id').value;
    eduIns = document.getElementById('edit_edu_institution').value;
    eduStart = document.getElementById('edit_edu_start').value;
    eduEnd = document.getElementById('edit_edu_end').value;
    eduDegree = document.getElementById('edit_edu_degree').value;
    eduDesc = document.getElementById('edit_edu_description').value;
    education = makeEducation(id, eduIns, eduStart, eduEnd, eduDegree, eduDesc);
    data = updateEducation(id, education);
    saveResumeData(data);
    loadEducationToDropDown(data.educations);
    closeModalEducation();
}


//progress bar
function updateProgressBar2() {
    const input = document.getElementById('skillProgressEd');
    const progressBar = document.getElementById('progress-bar2');
    let value = input.value;

    if (value < 1) value = 1;
    if (value > 100) value = 100;

    progressBar.style.width = value + '%';
}

// on load

document.addEventListener('DOMContentLoaded', function() {
    // Function to execute on page load
    initBasicInfos();
    clearBasic();
    clearSkill();
    clearWork();
    clearEducation();
    data = loadResumeData();
    loadSkillsToDropDown(data.skills);
    loadWorksToDropDown(data.experiences);
    loadEducationToDropDown(data.educations);

})






