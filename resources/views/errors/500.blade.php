@extends('layouts.error')

@section('title', 'Internal Server Error')
@section('code', '500')
@section('message', 'Internal Server Error')
@section('imageUrl', Vite::asset('resources/images/errors/500.png'))
