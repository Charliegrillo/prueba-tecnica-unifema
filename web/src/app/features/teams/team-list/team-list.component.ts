import { Component, OnInit } from '@angular/core';
import { Team, TeamService } from './../../../services/teams.service';
import { CommonModule } from '@angular/common';
import { TeamFormComponent } from '../team-form/team-form.component';

@Component({
  selector: 'app-team-list',
  standalone: true,
  imports: [CommonModule, TeamFormComponent],
  template: `
    <div class="row">
      <div class="col-md-8">
        <h2>Equipos</h2>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>
              <tr *ngFor="let team of teams">
                <td>{{ team.name }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <app-team-form (teamAdded)="loadTeams()"></app-team-form>
      </div>
    </div>
  `
})
export class TeamListComponent implements OnInit {
  teams: Team[] = [];

  constructor(private teamService: TeamService) {}

  ngOnInit(): void {
    this.loadTeams();
  }

  loadTeams(): void {
    this.teamService.getTeams().subscribe((teams: Team[]) => {
      this.teams = teams;
    });
  }
}