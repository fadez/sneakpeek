@extends('layouts.error')

@section('title', 'Forbidden')
@section('code', '403')
@section('message', config('app.debug') && $exception->getMessage() ? $exception->getMessage() : 'Forbidden')
