import { Routes } from '@angular/router';
import { TeamListComponent } from './features/teams/team-list/team-list.component';
import { StandingsListComponent } from './features/standings/standings-list/standings-list.component';

export const routes: Routes = [
  { path: '', redirectTo: '/', pathMatch: 'full' },
  { path: 'teams', component: TeamListComponent },
  { path: 'standings', component: StandingsListComponent }
];