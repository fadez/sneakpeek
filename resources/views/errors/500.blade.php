@extends('layouts.error')

@section('code', '500')
@section('title', 'Internal Server Error')
@section('message', 'Internal Server Error')
@section('imageUrl', Vite::asset('resources/images/errors/500.png'))
