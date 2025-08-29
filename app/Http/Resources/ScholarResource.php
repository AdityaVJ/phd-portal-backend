<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScholarResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'registration_number' => $this->registration_number,
            'registration_date' => $this->registration_date,
            'is_active' => $this->is_active,
            'is_scholarship_complete' => $this->is_scholarship_complete,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // only return required supervisor info
            'active_supervisor' => $this->whenLoaded('activeSupervisor', function () {
                return [
                    'id' => $this->activeSupervisor->supervisor_id,
                    'name' => $this->activeSupervisor->supervisor->name,
                    'assigned_date' => $this->activeSupervisor->assigned_date,
                    'assigned_by' => $this->activeSupervisor->assignedBy->name
                ];
            }),
        ];
    }
}
