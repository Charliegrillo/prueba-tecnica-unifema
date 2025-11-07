import { Component, EventEmitter, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { TeamService } from '../../../services/teams.service';

@Component({
  selector: 'app-team-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  template: `
    <div class="card">
      <div class="card-header">
        <h5>Nuevo Equipo</h5>
      </div>
      <div class="card-body">
        <form [formGroup]="teamForm" (ngSubmit)="onSubmit()">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" class="form-control" formControlName="name">
            <div *ngIf="teamForm.get('name')?.invalid && teamForm.get('name')?.touched" class="text-danger">
              Nombre es requerido
            </div>
          </div>
          
          <button type="submit" class="btn btn-primary" [disabled]="teamForm.invalid">
            Crear Equipo
          </button>
        </form>
      </div>
    </div>
  `
})
export class TeamFormComponent {
  @Output() teamAdded = new EventEmitter<void>();
  teamForm: FormGroup;
  currentYear = new Date().getFullYear();

  constructor(
    private fb: FormBuilder,
    private teamService: TeamService
  ) {
    this.teamForm = this.fb.group({
      name: ['', Validators.required]
    });
  }

  onSubmit(): void {
    if (this.teamForm.valid) {
      this.teamService.createTeam(this.teamForm.value).subscribe({
        next: () => {
          this.teamForm.reset();
          this.teamAdded.emit();
        },
        error: (error: any) => {
          console.error('Error creating team:', error);
        }
      });
    }
  }
}