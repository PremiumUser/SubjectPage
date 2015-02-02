Here is documentation of SubjectPage project, final version. 
Author:Bartłomiej Żak
Files of this project include:
- One plugin that adds button "Subject Administrating" in Settings menu
- One modification to wordpress social login.
- 5 new theme pages that are pages ready to be used right away, with all code and words written already. Users have no freedom of choosing how feature will be used.
To sum it up all code writen is one plugin, one modified file in wordpress social login plugin and five theme pages, each having some of the functionalities applied. Those five can be added to any theme that supports page-templates. Another words it is extension to a theme.


Note, that plugin can download data from usosweb only if wordpress social login plugin is running by that moment. Note also that if user wants to see each part of his created profile he requires pages built upon those themes.

How to use:
Here, on git-hub you have all files already set up. If you want to do it by yourself do as follows:
- Copy plug, as it is plugin admin needs.
- Install wordpress social plugin, version with usosweb file that can be used as a provider.
- Copy diff/Usosweb.php file into wordpress-social-login/hybridauth/Hybrid/Providers
- Copy all other files into page-templates folder of theme that you are using
- Make one page using each of the 5 themes to have each functionality.

About functionalities:
When it comes to all the functionalities that had to be implemented by default:
Administrator can:
= Award badges
= Give grades
= Send messages for the entire group just by "one click of a mouse"
= Create events
Users can:
= See their grades, messages, events in a timetable
= See all of the grades from all of the userst from the test that they have participated and got grades
= Erase any message they have in inbox
Features that had to be implemented but are not:
- Alerts
- Notes
Features that didn't have to be implemented, yet they are:
+ Forum
+ Minesweeper
While minesweeper is kind of joke and experiment, can it be made so easily, then the forum is really good tool that can suplement "notes" functionality in some way. As in normal forum you can create a thread and write newest reply for the thread selected. This allows user to make debates, argue, with some results.

Note that each and every functionality requires user to have his account attached to usosweb, elsewise no interesting data shall be visible to the naked eye.
