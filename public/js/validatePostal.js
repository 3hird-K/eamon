function validateZipCodes() {
    const error = document.getElementById("validateError").innerText;
    const fromZip = document.getElementById("zipcodeFrom");
    const toZip = document.getElementById("zipcodeTo");

    const $inputFromZip = document.getElementById("inputFromZip");
    const $inputToZip = document.getElementById("inputToZip");

    const err1 = "Invalid recipient postal code";
    const err2 = "Invalid shipper postal code.";
    // const err3 = "An unknown error occurred.";

    if (error != null) {
        console.log(error);

        if (error === err1) {
            toZip.classList.add("is-invalid");
            console.log(err1);
        }
        if (error === err2) {
            fromZip.classList.add("is-invalid");
            console.log(err2);
        }
    } else {
        fromZip.classList.remove("is-invalid");
        toZip.classList.remove("is-invalid");
    }
}
validateZipCodes();
