function mostrarFormSh(num) {
    const formularios = ['formShalom', 'formOlva', 'formMarvisur'];
    document.querySelectorAll('#viewPedidos .forms .formulario').forEach(div => div.classList.remove('active'));
    document.querySelectorAll('#viewPedidos .menu button').forEach(btn => btn.classList.remove('active'));
    document.getElementById(formularios[num - 1]).classList.add('active');
    document.querySelectorAll('#viewPedidos .menu button')[num - 1].classList.add('active');
  }
  