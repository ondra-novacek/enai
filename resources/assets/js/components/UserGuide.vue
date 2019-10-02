<template>
    <div class="container custom-background">
        <br><br>
        <h4 id="header">Detailed explaination of how to use this app</h4>

        <p>
            <small>! Note that the terms survey, test and questionare refers to the same thing in this tutorial.</small>
            <br>
            <small>! Note that this documenation might not be up to date.</small>
        </p>
        <hr>
        <h5 class="headers">Overview</h5>
        <strong>Intro</strong>
        <p>This application is divided in two parts. First part is accessible for everyone and contains the created surveys itself. 
            Second part is only for authenticated users and contains things like creating surveys, creating evaluations or seeing the results. 
            This guide is only for the second part and contains all routes after /edit.</p>

        <strong>Navbar</strong>
        <p>Navbar currently holds three options to choose from plus logout option on the right side.
            <ul>
                <li>First option 'ENAI' links to the official Academic integrity page.</li>
                <li>Second option 'Menu' is a main page, where you have many options to choose from. This is also the default page when you log in. </li>
                <li>Third option 'Tests' links to index page of tests, that has been created.</li>
            </ul>
        </p>

        <strong>Menu</strong>
        <p>In this page you have many options to select from:
            <ul>
                <li>Tests - edit or create tests for student/teacher/management</li>
                <li>Feedback for section - edit feedbacks for specific sections</li>
                <li>Feedback for overall - edit feedbacks for the whole test</li>
                <li>See results - export and view results + see interesting graphs</li>
                <li>Help - this documentation</li>
            </ul>
        </p>  
        
        <strong>Select test for student/teacher/management</strong>
        <p>After you have selected for whom you will be editing a test, you will be redirected to the new page. 
        </p>

        <hr>
        <h5 class="headers">Edit specific test</h5>
        <strong>Test description</strong>
        <p>At the top of the page next to the header is formattable textarea for description of the test. 
            This text will be shown as the first page, when user selects the test. 
            Text can be formatted and will be rendered appropriately. Save the results by clicking 'Save'.
        </p>

        <strong>Create new section</strong>
        <p>
            You can create new section by inputing section name and section value and pressing button 'Add section'. Section will be displayed without the need to refresh the page.
        </p>

        <strong>Sections</strong>
        <p>Once you have created sections, they will be displayed under the header. Sections are divided into rows with three columns - section name, section value and whether the section has been used in that particular survey.
            If at least one question has been added into the section in the survey, then it means it is included in the survey otherwise it is not. 
            Order of the sections can be changed by clicking on buttons with arrows.
        </p>

        <strong>One section</strong>
        <p>You can select specific section by clicking anywhere in the row with the desired section name. New box will be displayed. Then you have multiple options:
            <ul>
                <li>Edit section values</li>
                <p>- by clicking on 'Edit section name and value' new two inputs will appear. Enter new value(s) and click 'save' to save changes.</p>
                <li>Delete section</li>
                <p>If you want to delete a section, click on the button 'Delete section' in the right upper corner. Section can be deleted only if it contains no questions and 
                    if it is not used in any other test.
                </p>
                <li>Add new question</li>
                <p>After clicking on button 'Add question' small window will appear.</p>
                <li>Edit existing question</li>
                <p>Click on the text of question that you would like to edit. Small window will appear.</p>
            </ul>
        </p>

        <strong>Demographic section</strong>
        <p>This is specific section from every other section. This section is already partly created. 
            You can find it as the first section. Clicking on it will display questions for this section.
            There are already 3 questions, that cannot be deleted. You can add new question in a same way as in normal section.
            However, there are different question types to choose from - open question, or question with dropdown list. Also this section is not being evaluated.
        </p>

        <strong>Add new question</strong>
        <p>Once you click on 'Add question', you will have two option for adding new question. 
            First one is creating the whole question from start. You choose question value, text and type. Save question by hitting 'Add to section'.
            Second option is to add question that already has been created in another test, but in the same section 
            (e.g. In test for teacher in section called for example 'Intro' has been created question. Now in test for student in the section 'Intro' you can add the same question from the dropdown list.).
            Options for a question can be added in Edit question part.
        </p>

        <strong>Add options to question</strong>
        <p>Once you create new question, close the form. Newly created question will now appear in the section. Click on that question and new form will appear. 
            There you can add options to a question by inputing option text and value and clicking on the green button 'Add'.
            In matrix question it is little different. 
            You can choose how many columns there are and possibly name them. (default are numbers)
            You add rows and then u can add value and feedback for every matrix cell by clicking on edit button next to row text. 
        </p>

        <strong>Edit option</strong>
        <p>If you need to edit option, you can edit it in the same dialog as you create an option. Just change the existing values and it will be changed automatically.
            Also if you need to delete the option, click on the 'X' button. The button next to 'X' is for creating feedback to the option. If you click on it, new input will be shown, 
            where you can add the text you want the user to see as a feedback for that selected option. Then confirm it by clicking on button 'Confirm'.
            If you need to edit row text in matrix question, double click on the text and new form will appear.
        </p>

        <strong>Edit question</strong>
        <p>In the section, where you see all the question for that section, click on the question you wish to edit. New dialog will appear, where you can change the values and 
            add or edit options. The changes you make to question will be saved automatically just by editing the desired input.
        </p>

        <strong>Delete question</strong>
        <p>Delete question by clicking on the trash button next to the question text.</p>

        <strong>Move question to different section</strong>
        <p>Move question to another section by clicking on the button with two horizontal arrows.</p>

        <hr>
        <h5 class="headers">Add feedbacks</h5>
        <strong>Question feedback</strong>
        <p>If you need to add feedback to options for a question, go to the question edit. There on the right side next to the button 'x' that deletes question, is 
            button with text bubble icon. By clicking on it, new input for creating feedback for that particular option will be displayed. Add the feedback text and click 
            on 'confirm' to save it.
            In questions type M from N, there are 2 question feedbacks - for selecting and for not selected that option. First one is for selected.
        </p>

        <strong>Section feedback</strong>
        <p>From edit menu select 'Feedback for sections'. On the top of the page select desired test (student/teacher/management). 
            Under that selection is form for creating new feedbacks. First input is for feedback formattable text, second is number of points, which determines, whether this feedback will 
            be displayed or not. Let's call this input min_pts. It works like this: 
            <ul>
                <li>Section will be evaluated as sum of points for every question in that section based on the chosen answers by respondent.</li>
                <li>In the database application will find feedback with value of min_pts closest to those points, 
                    but those points have to be lower or equal to the number of achieved points for the section by user.</li>
            </ul> 
            <p>Under that form are displayed every feedback for that test in white boxes.
                Each box represents one section. Toggle the box with plus/minus icon in the right corner. If you wish to deleted any feedback, click on the trash button.
                If you wish to edit the text, double click on it and new form will appear.
            </p>
        </p>

        <strong>Feedback for the whole test</strong>
        <p>From edit menu click on 'Feedback overall'. It works almost the same as section feedbacks. There is only one more input - number of starts.
            This number of stars will be shown to the respondent, if that feedback is generated. Also here one box represents one feedback.
        </p>

        <hr>
        <h5 class="headers">Results</h5>
        <strong>Export</strong>
        <p>You can export all results from the database into Microsoft Excel file (.xlsx) and .csv file.
            From edit menu click on 'See results'. Then click on export data and select the format.
            New window will pop up, where you select, where you want that file to be stored in your local machine.
            You can also preview first 50 results by clicking on 'Show respondents data'.
            
            There are also some graphs included, click on the button that works as graph toggle and view the graph.
        </p>
    </div>
</template>

<script>

</script>

<style>
    .custom-background{
        background-color: white;
        padding-bottom: 50px;
    }

    #header{
        text-align: center
    }

    .headers{
        text-align: center
    }

</style>