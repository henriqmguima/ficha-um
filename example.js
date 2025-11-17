try {
  const response = await fetch("/admin/usuarios/store", {
    method: "POST",
    body: formData,
  });

  const json = await response.json();

  if (json.success) {
    campoSenha.innerText = json.senha;
    form.style.display = "none";
    resposta.style.display = "block";
  } else {
    alert(json.message || "Erro ao cadastrar.");
  }
} catch (error) {
  alert("Erro na requisição");
}
