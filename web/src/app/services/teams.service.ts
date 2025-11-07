import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

export interface Team {
  id?: number;
  name: string;
  city: string;
  founded_year: number;
}

@Injectable({
  providedIn: 'root'
})
export class TeamService {
  private apiUrl = environment.API_URL;

  constructor(private http: HttpClient) { }

  getTeams(): Observable<Team[]> {
    console.log('Fetching teams from API');
    const data:any =  this.http.get<Team[]>(`${this.apiUrl}/teams`);
    data.subscribe((res: any) => console.log(res));
    return this.http.get<Team[]>(`${this.apiUrl}/teams`);
  }

  createTeam(team: Team): Observable<Team> {
    return this.http.post<Team>(`${this.apiUrl}/teams`, team);
  }
}