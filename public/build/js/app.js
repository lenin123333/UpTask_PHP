const mobieMenuBtn=document.querySelector("#mobile-menu"),sidebar=document.querySelector(".sidebar"),cerrarMenuBtn=document.querySelector("#cerrar-menu");mobieMenuBtn&&mobieMenuBtn.addEventListener("click",(function(){sidebar.classList.add("mostrar")})),cerrarMenuBtn&&cerrarMenuBtn.addEventListener("click",(function(){sidebar.classList.add("ocultar"),setTimeout(()=>{sidebar.classList.remove("mostrar"),sidebar.classList.remove("ocultar")},1e3)})),window.addEventListener("resize",(function(){document.body.clientWidth>=768&&sidebar.classList.remove("mostrar")}));