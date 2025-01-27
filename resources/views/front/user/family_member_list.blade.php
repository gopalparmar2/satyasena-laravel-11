@if ($familyMembers->count() == 1)
<p class="family-list-header">Added</p>

<div class="family-details" id="divFamilyMemberDetails">
@endif

@foreach ($familyMembers as $familyMember)
    <div class="family-list-item-container" id="divFamilyMemberContiner_{{ $familyMember->id }}">
        <div class="row-item">
            <div class="label">Full Name </div>
            <div class="value">{{ $familyMember->name }}</div>
        </div>

        <div class="divider"></div>

        <div class="row-item">
            <div class="label"> Relationship</div>
            <div class="value">{{ $familyMember->relationship ? $familyMember->relationship->name : '' }}</div>
        </div>

        <div class="divider"></div>

        <div class="row-item">
            <div class="label">D.O.B | Age</div>
            <div class="value">{{ date('d-m-Y', strtotime($familyMember->dob)) }} | {{ $familyMember->age . 'Y' }}</div>
        </div>

        <div class="divider"></div>

        <div class="row-item">
            <div class="label">Mobile number</div>
            <div class="value">{{ $familyMember->mobile_number }}</div>
        </div>

        <div class="dashed-divider"></div>

        <div class="btn-wrapper">
            <div class="spacer"></div>

            <div class="family-form-list-btn-container">
                <div class="action-btn left-btn btnEditFamilyMember" data-id="{{ $familyMember->id }}">Edit</div>
                <div class="action-btn right-btn btnDeleteFamilyMember" data-id="{{ $familyMember->id }}">Delete</div>
            </div>
        </div>
    </div>
@endforeach

@if ($familyMembers->count() == 1)
</div>
@endif
