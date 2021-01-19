(() => {

    //Listen for page load

    window.addEventListener('load', get_page_init);

    function get_page_init(){

        let page_url = window.location.href.split('/').pop().split('?')[0];
        console.log(page_url);

        //Basically a router for getting data from the backend
        switch(page_url){
            case('teams.homepage.php'): init_teams_homepage_php(); break;
            case('dashboard.php'): init_dashboard_php(); break;
            case('team.php'): init_team_php(); break;
        }
    }


    //Get data and integrate into the frontend
    //TODO : Get rid of the php text in the function names
    function init_teams_homepage_php(){

        //Get team info for this user
        let get_teams_req = new_req();
        
        let integrate_teams = function(data) {
            //parse data
            let teams = JSON.parse(data);

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
                let team_name = document.createElement('div');
                team_name.classList.add('teamName');
                let team_name_link = document.createElement('a');
                let team_name_link_text = document.createElement('h4');
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
                let team_logo;
                if(curr_team.logo_status == 1){
                    team_logo = document.createElement('img');
                    team_logo.src = "/Interact/teams/profileImages/" + curr_team.id + '.jpg';
                }
                else {
                    team_logo = document.createElement('i');
                    team_logo.classList.add('fa');
                    team_logo.classList.add('fa-users');
                }
                team_logo.classList.add('teamLogo');

                team_name_link.href = './team.php?team=' + curr_team.name;
                team_name_link_text.innerText = curr_team.name;

                team_wins_text.innerText = curr_team.wins;
                team_losses_text.innerText = curr_team.losses;

                more_info_link.href = './team.php?team=' + curr_team.name;

                //Structure through appends
                team.appendChild(team_logo_wrapper);
                team_name_link.appendChild(team_name_link_text);
                team_name.appendChild(team_name_link);
                team.append(team_name);
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

        get_teams_req.open('GET', 'Backend/get_user_teams.php', true);
        get_teams_req.send();
    }

    function init_dashboard_php(){
        //Get user data
        let res;
        let user_data_req = new_req();

        let handle_user_data = function(data){
            //Parse data
            res = JSON.parse(data);

            //Separate user info and tournament info
            let user_info = res.user;
            let user_tournaments = res.tournaments;

            //integrate user logo
            
                //Get parent
                let profile = document.getElementById('profile');

                //Build wrap
                let logo_wrap = document.createElement('div');
                    logo_wrap.classList.add('spinEl');
                    logo_wrap.onclick = 'redir(1)';

                let logo;
                
                //Check if logo set
                if(user_info.logo_status == true){
                    //Build logo
                    logo = document.createElement('img');
                    logo.id = 'profilePic';
                    logo.alt = 'Profile Picture';
                    //Add data
                    logo.src = '/Interact/users/profileImages/' + user_info.user_id + '.jpg';
                }
                else{
                    //build logo
                    logo = document.createElement('i');
                    logo.id = 'profilePic';
                    logo.classList.add('fa');
                    logo.classList.add('fa-user');
                }

                //Append
                logo_wrap.appendChild(logo);
                profile.appendChild(logo_wrap);

            //Separate tournament games by date
            let user_matches_past = [];
            let user_matches_future = [];
            

            //set curr date and time
            let start_cmp = new Date();

            for(let t in user_tournaments){
                let ct = user_tournaments[t];
                //go through each round
                for(let r in ct.rounds){
                    let cr = ct.rounds[r];
                    //Go through each match
                    for(let m in cr.matches){
                        let cm = cr.matches[m];
                        //check if start hasn't been set
                        if(cm.start_date == null){
                            user_matches_future.push(cm);
                        }
                        else{
                            //set start date to compare
                            let cm_start = new Date(cm.start_date + 'T' + cm.start_time);
                            if(cm_start >= start_cmp){
                                //add to futures
                                user_matches_future.push(cm);
                            }
                            else{
                                //add to past
                                user_matches_past.push(cm);
                            }
                        }
                    }
                }
            }


            //integrate upcoming games data
            let future_match_list = document.getElementById('upcomingGames').children[1];

            for(let f in user_matches_future){
                let cm = user_matches_future[f];
                
                let match = document.createElement('upcomingGame');
                let time_wrap = document.createElement('time');
                let time_text = document.createElement('h3');
                let teams_wrap = document.createElement('div');
                let team_a = document.createElement('div');
                let team_a_name = document.createElement('h3');
                let team_sep = document.createElement('div');
                let team_sep_text = document.createElement('h3');
                let team_b = document.createElement('div');
                let team_b_name = document.createElement('h3');
                let match_more = document.createElement('div');
                let match_more_logo = document.createElement('i');

                match.classList.add('upcomingGame');
                time_wrap.classList.add('time');
                teams_wrap.classList.add('teams');
                team_a.classList.add('teamA');
                team_sep.classList.add('separator');
                team_b.classList.add('teamB');
                match_more.classList.add('more');
                match_more_logo.classList.add('fa');
                match_more_logo.classList.add('fa-arrow-right');

                //Add data
                time_text.innerText = cm.start_time;
                team_a_name.innerText = cm.team_a.name;
                team_sep_text.innerText = 'vs';
                team_b_name.innerText = cm.team_b.name;

                //Append
                match.appendChild(time_wrap);
                time_wrap.appendChild(time_text);
                match.appendChild(teams_wrap);
                teams_wrap.appendChild(team_a);
                team_a.appendChild(team_a_name);
                teams_wrap.appendChild(team_sep);
                team_sep.appendChild(team_sep_text);
                teams_wrap.appendChild(team_b);
                team_b.appendChild(team_b_name);
                match.appendChild(match_more);
                match_more.appendChild(match_more_logo);
                future_match_list.appendChild(match);

            }




            //integrate invitations data
            let user_invitations = res.invitations;

            let invitation_list = document.getElementById('invitations').children[1];
            
            for(let i in user_invitations){
                let ci = user_invitations[i];

                // <div class='invitation'>
                //         <div class='invitor'><h3>" . $row['username'] . "</h3></div>
                //         <div class='type'><h3>" . $row['type'] . "</h3></div>
                //         <div class='options'>
                //           <div class='accept'><a href='" . $nextPage . "'><i class='fa fa-check'></i></a></div>
                //           <div class='reject'><i class='fa fa-times'></i></div>
                //         </div>
                //         </div>

                //Build el
                let invitation = cEl('div', {"classes": 'invitation'});
                let invitor = cEl('div', {"classes": 'invitor'});
                let invitor_name = cEl('h3', {});
                let invitation_type = cEl('div', {"classes": 'type'});
                let invitation_type_text = cEl('h3', {});
                let invitation_options = cEl('div', {"classes": 'options'});
                let option_accept_wrap = cEl('div', {"classes": 'accept'});
                let option_accept = cEl('a', {});
                let accept_logo = cEl('i', {"classes": ['fa', 'fa-check']});
                let option_decline_wrap = cEl('div', {"classes": 'reject'});
                let option_decline = cEl('a', {});
                let decline_logo = cEl('i', {"classes": ['fa', 'fa-times']});

                //Add data
                invitor_name.innerText = ci.invitor.username;
                invitation_type_text.innerText = ci.type;

                //link options
                switch(ci.type){
                    case('Team'):
                        option_accept.href = "acceptTeamInv.php?team=" + ci.team.name + "&player=" + user_info.user_id;

                        //TODO : decline option
                        break;

                    case('Tournament'):
                        option_accept.href = "newPlayerTournamentAssociation.php?team=" + ci.joining_team.name + "&player=" + user_info.username + "&tournamentName=" + ci.tournament.name;

                        //TODO : decline option
                        break;

                    //TODO : case match
                }

                //TODO Add later - team, tournament, or match identifier (name, time, etc)

                //Append
                invitation.appendChild(invitor);
                invitor.appendChild(invitor_name);
                invitation.appendChild(invitation_type);
                invitation_type.appendChild(invitation_type_text);
                invitation.appendChild(invitation_options);
                invitation_options.appendChild(option_accept_wrap);
                option_accept_wrap.appendChild(option_accept);
                option_accept.appendChild(accept_logo);
                invitation_options.appendChild(option_decline_wrap);
                option_decline_wrap.appendChild(option_decline);
                option_decline.appendChild(decline_logo);

                invitation_list.appendChild(invitation);
            }



            //integrate history data
            let past_match_list = document.getElementById('history').children[1];

            for(let p in user_matches_past){
                let cm = user_matches_past[p];
                
                let match = document.createElement('upcomingGame');
                let time_wrap = document.createElement('time');
                let time_text = document.createElement('h3');
                let teams_wrap = document.createElement('div');
                let team_a = document.createElement('div');
                let team_a_name = document.createElement('h3');
                let team_sep = document.createElement('div');
                let team_sep_text = document.createElement('h3');
                let team_b = document.createElement('div');
                let team_b_name = document.createElement('h3');
                let match_more = document.createElement('div');
                let match_more_logo = document.createElement('i');

                match.classList.add('upcomingGame');
                time_wrap.classList.add('time');
                teams_wrap.classList.add('teams');
                team_a.classList.add('teamA');
                team_sep.classList.add('separator');
                team_b.classList.add('teamB');
                match_more.classList.add('more');
                match_more_logo.classList.add('fa');
                match_more_logo.classList.add('fa-arrow-right');

                //Add data
                time_text.innerText = cm.start_time;
                team_a_name.innerText = cm.team_a.name;
                team_sep_text.innerText = 'vs';
                team_b_name.innerText = cm.team_b.name;

                //Append
                match.appendChild(time_wrap);
                time_wrap.appendChild(time_text);
                match.appendChild(teams_wrap);
                teams_wrap.appendChild(team_a);
                team_a.appendChild(team_a_name);
                teams_wrap.appendChild(team_sep);
                team_sep.appendChild(team_sep_text);
                teams_wrap.appendChild(team_b);
                team_b.appendChild(team_b_name);
                match.appendChild(match_more);
                match_more.appendChild(match_more_logo);
                past_match_list.appendChild(match);

            }
        }

        handle_req(user_data_req, handle_user_data);

        user_data_req.open('GET', 'Backend/get_dashboard.php');
        user_data_req.send();
    }

    function init_team_php(){
        let url_params = getURLParams();

        let team_req = new_req();

        let team_req_handler = function(data){
            let team = JSON.parse(data);
            console.log("Team : ", team);
        }

        handle_req(team_req, team_req_handler);

        team_req.open('GET', "Backend/get_team.php?" + url_params[0].parameter + '=' + url_params[0].value);

        team_req.send();
    }



    //other functionality

    function new_req(){
        return new XMLHttpRequest();
    }

    function handle_req(req, callback){
        req.onreadystatechange = () => {
            if(req.readyState == 4 && req.status == 200){
                //console.log("Server Response : ", req.responseText);
                callback(req.responseText);
            }
        }
    }

    function print_response(res){
        console.log(res);
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

    function extract_time_fragments(time){
        let fragments = time.split(':');
        return {
            "hours": fragments[0],
            "minutes": fragments[1]
        }
    }

    function cEl(tag, load){
        let el = document.createElement(tag);
        if(load.classes){
            if(Array.isArray(load.classes)){
                for(let c in load.classes){
                    el.classList.add(load.classes[c]);
                }
            }
            else if(typeof(load.classes) == "string"){
                el.classList.add(load.classes);
            }
        }
        return el;
    }

    function getURLParams(){
        let url = new URL(window.location);
        let params_raw = url.search.substring(1).split('&');
        let params = [];
        for(let p in params_raw){
            params.push(splitURLParam(params_raw[p]));
        }
        return params;
    }

    function splitURLParam(param){
        let sep = param.split('=');
        return {
            "parameter": sep[0],
            "value": sep[1]
        }
    }
})()