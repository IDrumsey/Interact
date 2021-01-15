(() => {

    //Listen for page load

    window.addEventListener('load', get_page_init);

    function get_page_init(){

        let page_url = window.location.href.split('/').pop().split('?')[0];

        //Basically a router for getting data from the backend
        switch(page_url){
            case('teams.homepage.php'): init_teams_homepage_php();
        }
    }


    //Get data and integrate into the frontend
    function init_teams_homepage_php(){
        console.log("initializing teams.homepage");

        //Get team info for this user
        let get_teams_req = new_req();
        
        let integrate_teams = function(data) {
            //parse data
            let teams = JSON.parse(data);
            console.log(teams);

            //Get team list element
            let team_list = document.getElementById('teamList');

            //for each team, create el and
            for(let i in teams){
                let curr_team = teams[i];
                //create structure
                let team = document.createElement('div');
                team.classList.add('team');
                let team_logo_wrapper = document.createElement('div');
                team_logo_wrapper.classList.add('teamLogoWrapper');
                let team_logo = document.createElement('img');
                team_logo.classList.add('teamLogo');
                let team_name = document.createElement('div');
                team_name.classList.add('teamName');
                let team_name_link = document.createElement('a');
                let team_wins = document.createElement('div');
                team_wins.classList.add('teamWon');
                let team_wins_text = document.createElement('h4');
                let team_losses = document.createElement('div');
                team_losses.classList.add('teamLost');
                let team_losses_text = document.createElement('h4');
                let more_info = document.createElement('div');
                more_info.classList.add('more');
                let more_info_link = document.createElement('a');
                let more_info_icon = document.createElement('i');
                more_info_icon.classList.add('fa');
                more_info_icon.classList.add('fa-arrow-right');


                //fill in data
                if(curr_team.logo_status == 1){
                    console.log("logo set");
                    team_logo.src = "/Interact/teams/profileImages/" + curr_team.name;
                }

                team_name_link.innerText = curr_team.name;
                team_name_link.href = './team.php?team=' + curr_team.name;

                team_wins_text.innerText = curr_team.wins;
                team_losses_text.innerText = curr_team.losses;

                more_info_link.href = './team.php?team=' + curr_team.name;

                //Structure through appends
                team.appendChild(team_logo_wrapper);
                team.appendChild(team_name_link);
                team.appendChild(team_wins);
                team.appendChild(team_losses);
                team.appendChild(more_info);



                team_logo_wrapper.appendChild(team_logo);
                team_name.appendChild(team_name_link);
                team_wins.appendChild(team_wins_text);
                team_losses.appendChild(team_losses_text);
                more_info.appendChild(more_info_link);
                more_info_link.appendChild(more_info_icon);

                //Append onto list

                team_list.appendChild(team);
            }
        }

        handle_req(get_teams_req, integrate_teams);

        get_teams_req.open('GET', 'Backend/get_teams.php', true);
        get_teams_req.send();
    }




    //other functionality

    function new_req(){
        let req = create_request();
        
        return req;
    }

    function handle_req(req, callback){
        req.onreadystatechange = () => {
            if(req.readyState == 4 && req.status == 200){
                console.log("Server Response : ", req.responseText);
                callback(req.responseText);
            }
        }
    }

    function print_response(res){
        console.log(res);
    }

    function create_request(){
        return new XMLHttpRequest();
    }


    function create_complex_element(parent_element_ref, child_element){
        let child_ref = create_element(child_element['tag'], child_element['classes'], child_element['ref']);
        parent_element_ref.appendChild(child_ref);
        for(let i in child_element['children']){
            create_complex_element(child_ref, child_element['children'][i]);
        }
    }


    function create_element(tag, classes, id, innerText){
        //Create element
        let el = document.createElement(tag);

        //Add classes
        for(let i in classes){
            el.classList.add(classes[i]);
        }

        //Add id
        el.id = id;

        el.innerText = innerText;

        return el;
    }
})()