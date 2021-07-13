# Http-Client-Example

### Installation and configuration

**1. Clone repository:**
```bash
git clone git@github.com:kdabek/http-client-example.git
```

**2. Update configuration**

Update `config.php` and fill your `accessToken`

**3. Build Docker image**
```bash
docker build -t http-client-example .
```

**4. Run Docker image**
```bash
docker run -it --rm --name my-http-client-app http-client-example
```
