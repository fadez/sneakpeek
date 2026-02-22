@extends('layouts.error')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('message', 'Service Unavailable')
@section('imageUrl', Vite::asset('resources/images/errors/503.png'))
