 document.querySelector("form").addEventListener("submit", function(e) {
            const tipo = document.querySelector('select[name="tipo"]').value;

            if (tipo === "cliente") {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                }).then(response => response.text())
                .then(data => {
                    window.location.href = "index.php?pg=1009";
                }).catch(error => {
                    console.error("Error:", error);
                    alert("Ocurrió un error al registrar el usuario.");
                });
            }
        });