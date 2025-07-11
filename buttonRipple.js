document.querySelectorAll("button.ripple").forEach(button => {
    button.addEventListener("click", function (e) {
        const ripple = document.createElement("span");
        ripple.classList.add("ripple-circle");
        ripple.style.width = ripple.style.height = Math.max(button.offsetWidth, button.offsetHeight) + "px";
        ripple.style.left = `${e.clientX - button.getBoundingClientRect().left}px`;
        ripple.style.top = `${e.clientY - button.getBoundingClientRect().top}px`;
        this.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
});

