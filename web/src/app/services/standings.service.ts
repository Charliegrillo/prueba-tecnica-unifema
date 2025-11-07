import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Standing {
  position: number;
  team_id: number;
  team_name: string;
  played: number;
  won: number;
  drawn: number;
  lost: number;
  goals_for: number;
  goals_against: number;
  goal_difference: number;
  points: number;
}

@Injectable({
  providedIn: 'root'
})
export class StandingsService {
  private apiUrl = environment.API_URL;

  constructor(private http: HttpClient) { }

  getStandings(): Observable<Standing[]> {
    return this.http.get<Standing[]>(`${this.apiUrl}/standings`);
  }
}