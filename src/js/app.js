

const mobieMenuBtn=document.querySelector('#mobile-menu');
const sidebar=document.querySelector('.sidebar');
const cerrarMenuBtn=document.querySelector('#cerrar-menu');
if(mobieMenuBtn){
    mobieMenuBtn.addEventListener('click',function(){
    sidebar.classList.add('mostrar');    
    });
}

if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click',function(){
        sidebar.classList.add('ocultar');
        setTimeout(() => {
         sidebar.classList.remove('mostrar');  
         sidebar.classList.remove('ocultar');   
        }, 1000);
    });
}

//Elimina la clse de mostrar en un tamaño de tablet y mayores


window.addEventListener('resize', function(){
    const anchoPantalla = document.body.clientWidth;
    if(anchoPantalla >= 768){
        sidebar.classList.remove('mostrar');
    }
});