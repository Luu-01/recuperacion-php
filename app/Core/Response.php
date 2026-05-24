<?php

namespace App\Core;

class Response
{
    protected int $status = 200;
    protected array $headers = [];
    protected ?string $redirectTo = null;
    protected array $flash = [];
    protected ?string $content = null;

    /* -----------------------------
       Configuración
       -----------------------------*/

    public function with(string $key, mixed $value): self
    {
        $this->flash[$key] = $value;
        return $this;
    }

    public function withInput(array $inputs): self
    {
        session()->flash('_old', $inputs);
        return $this;
    }

    public function withErrors(array $errors): self
    {
        session()->flash('_errors', $errors);
        return $this;
    }

    public function status(int $code): self
    {
        $this->status = $code;
        return $this;
    }

    public function header(string $key, string $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function redirect(string $url, int $status = 302): self
    {
        // URLs externas absolutas: se respetan tal cual.
        if (preg_match('#^https?://#i', $url)) {
            $this->redirectTo = $url;
        }
        // URLs internas ya generadas con url(): no se vuelve a añadir BASE_URL.
        elseif (str_starts_with($url, BASE_URL . '/') || $url === BASE_URL) {
            $this->redirectTo = $url;
        }
        // URLs internas relativas: se les añade BASE_URL una sola vez.
        else {
            $this->redirectTo = BASE_URL . '/' . ltrim($url, '/');
        }

        $this->status = $status;
        return $this;
    }

    public function json(mixed $data): self
    {
        $this->headers['Content-Type'] = 'application/json';
        $this->content = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    public function html(string $html): self
    {
        $this->content = $html;
        $this->headers['Content-Type'] = 'text/html; charset=utf-8';
        return $this;
    }

    /* -----------------------------
       Envío final
       -----------------------------*/

    public function send(): void
    {
        // Estado HTTP
        http_response_code($this->status);

        // Headers acumulados
        foreach ($this->headers as $k => $v) {
            header("$k: $v");
        }

        // Flash data
        foreach ($this->flash as $key => $value) {
            session()->flash($key, $value);
        }

        // Redirección
        if ($this->redirectTo) {
            header("Location: {$this->redirectTo}");
            exit;
        }

        // Contenido HTML o JSON
        if ($this->content !== null) {
            echo $this->content;
        }

        exit;
    }
}
