<script>
// wait until the page loads
window.addEventListener('load', function () {
    const formId = "surForm"; // ID of the form
    const url = location.href; //  href for the page
    const formIdentifier = `${url} ${formId}`; // Identifier used to identify the form
    const buttons = document.querySelectorAll(".saveform");
    //const finishbtn = document.querySelector('.clearform')
    const alertBox = document.querySelector(".alertLoad"); // select alert display div
    let form = document.querySelector(`#${formId}`); // select form
    let formElements = form.elements; // get the elements in the form

    /**
     * This function gets the values in the form
     * and returns them as an object with the
     * [formIdentifier] as the object key
     * @returns {Object}
     */
    const getFormData = () => {
        let data = { [formIdentifier]: {} }; // create an empty object with the formIdentifier as the key and an empty object as its value
        for (const element of formElements) {
        if (element.name.length > 0) {
            // workaround for 1 to 5 q
            if (element.name in data[formIdentifier]) {
                data[formIdentifier][element.name].push([element.value, element.checked]);
            } else {
                data[formIdentifier][element.name] = []
                data[formIdentifier][element.name][0] = [element.value, element.checked];
            }
        }
        }
        return data;
    };
    
    for (const btn of buttons) {
        btn.onclick = event => {
            event.preventDefault();
            data = getFormData();
            localStorage.setItem(formIdentifier, JSON.stringify(data[formIdentifier]));
            // const message = "Progress has been saved locally";
            // displayAlert(message);
        };
    }

    // finishbtn.onclick = event => {
    //     event.preventDefault();
    //     localStorage.setItem(formIdentifier, '');
    // };
    
    
    /**
     * This function displays a message
     * on the page for 1 second
     *
     * @param {String} message
     */
    const displayAlert = message => {
        alertBox.innerText = message; // add the message into the alert box
        alertBox.style.display = "block"; // make the alert box visible
        setTimeout(function() {
        alertBox.style.display = "none"; // hide the alert box after 1 second
        }, 2000);
    };

    /**
     * This function populates the form
     * with data from localStorage
     *
     */
    const populateForm = () => {
        if (localStorage.key(formIdentifier)) {
            const savedData = JSON.parse(localStorage.getItem(formIdentifier)); // get and parse the saved data from localStorage
            for (const element of formElements) {
                if (element.name in savedData) {
                    // q 1 to 5
                    if (savedData[element.name].length > 1){
                        let num = -1;
                        let it = 0;
                        for (const radio of savedData[element.name]) {
                            if (radio[1] == true) {
                                num = it;
                                radio
                            }
                            it += 1;
                        }

                        if (num > -1) {
                            els = document.getElementsByName(element.name)
                            els[num].checked = true
                        }
                    }
                    // other types
                    else {
                        element.value = savedData[element.name][0][0];
                        if (savedData[element.name][0][1]) {
                            element.checked = true
                        }
                    } 
                }
            }
            const message = "Form has been refilled with saved data!";
            displayAlert(message);
        }
    };
    document.onload = populateForm(); // populate the form when the document is loaded
})
</script>