@extends('layout')

@section('content')
    <section class="hero">
        <div class="content">
            <span class="eyebrow">Graduate School</span>
            <h1>Grading Sheet Submission Portal</h1>
            <p>
                A centralized workspace for faculty submissions, grading sheet approvals, and academic load
                tracking. Designed to keep approvals moving and records aligned with the academic calendar.
            </p>
            <div class="steps">
                <div class="step-card">
                    <strong>Submit Grading Sheets</strong>
                    <p>Upload grading sheets and confirm required fields to begin.</p>
                </div>
                <div class="step-card">
                    <strong>Monitor Submissions</strong>
                    <p>Review course details and ensure the academic year is correct.</p>
                </div>
                <div class="step-card">
                    <strong>Track Approvals</strong>
                    <p>Monitor status updates as submissions move to approval.</p>
                </div>
            </div>
            <div class="actions">
                <a class="primary-button" href="/app/login">Login</a>
            </div>
        </div>
    </section>
@endsection