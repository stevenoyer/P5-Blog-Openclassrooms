<?php 

namespace So\Blog\Interface;

interface Auth 
{
    public function register(array|object $params = []): bool;
    public function login(array|object $params = []): bool;
    public function verifyAccount(array|object $params = []): bool;
    public function logout(): bool;
}