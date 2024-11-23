# touchIn
## Como hacer peticiones en la api Usando la herramienta de Postman



## Dominio de produccion
Reemplaza `http://localhost:8000` por el siguiente dominio
<pre><code>https://mediumspringgreen-yak-566516.hostingersite.com<code></pre>
## Para registrar a un usuario

Mediante el método `POST` el endpoint es
<pre><code>http://localhost:8000/api/register </code></pre>
### Headers
```json
Accept: application/json
Content-Type: application/json
```
### Enviar el body en formato JSON:

<pre><code>{
  "name": "Ximena",
  "email": "xmanzo@ucol.mx",
  "pin": "1234",
  "fingerprint_template": "template_data"
}</code></pre>

---
### Respuesta esperada (código de salida):

```json
{
  "message": "User registered successfully.",
  "user_id": 4
}
```

si falta un campo o atributo en la solicitud podrias tener este error:
```json
{
    "error": "Missing required fields."
}
```


## Para autenticar un usuario

Mediante el método `POST` el endpoint es:
<pre><code>http://localhost:8000/api/auth/pin</code></pre>
### Headers
```json
Content-Type: application/json
```
Se necesita por el momento el `pin` solamente. En el body se tiene:

<pre><code>{
  "pin": "1234"
}</code></pre>

---

Si la respuesta es exitosa (`200`):

```json
{
    "message": "Authenticated",
    "user": {
        "id": 3,
        "name": "Ximena",
        "email": "xmanzo@ucol.mx",
        "fingerprint_template": "template_data",
        "pin": "1234",
        "created_at": "2024-11-20 00:41:57"
    }
}
```




## Para obtener la lista de usuarios completa
Mediante el método `GET` el endpoint es:
<pre><code> http://localhost:8000/api/users </pre></code>
### Headers
```json
Accept:application/json
```
Y el cuerpo de la respuesta es la lista de usuarios:

```json
[
    {
        "id": 3,
        "name": "Ximena",
        "email": "xmanzo@ucol.mx",
        "pin": "1234",
        "created_at": "2024-11-20 00:41:57"
    }
]
```


## Para obtener la informacion de un solo usuario

Mediante el método `GET` el endpoint es:
<pre><code> http://localhost:8000/api/users/id </pre></code>
### Headers
```json
Accept: application/json
```
Y el cuerpo de la respuesta es el usuario:
```json
{
    "id": 3,
    "name": "Ximena",
    "email": "xmanzo@ucol.mx",
    "fingerprint_template": "template_data",
    "pin": "1234",
    "created_at": "2024-11-20 00:41:57"
}
```