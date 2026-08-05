@extends('layouts.error')

@section('code', '503')
@section('title', 'Service Unavailable')
@section('message', 'Service Unavailable')
@section('imageUrl', Vite::asset('resources/images/errors/503.png'))
