
function disp(n) {
    if (n == 1) {
        document.getElementById('d1').classList.remove('d');
        document.getElementById('d2').classList.add('d');
        // document.getElementById('fha').required = true;
        // document.getElementById('no').required=false;
        // document.getElementById('na').required=false;
        // document.getElementById('ce').required=false;
        // document.getElementById('cv').required=false;
    }
    else {
        document.getElementById('d2').classList.remove('d');
        document.getElementById('d1').classList.add('d');
        // document.getElementById('fha').required = false;
        // document.getElementById('no').required = true;
        // document.getElementById('na').required = true;
        // document.getElementById('ce').required = true;
        // document.getElementById('cv').required = true;
    }
}
const form = document.querySelector('form');

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const captchaResponse = grecaptcha.getResponse();

    if (!captchaResponse > 0) {
        alert("Please complete reCAPTCHA");
    }
});
