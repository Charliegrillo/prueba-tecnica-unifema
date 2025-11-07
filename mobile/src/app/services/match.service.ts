import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { HttpClient, HttpParams } from '@angular/common/http';
import { map } from 'rxjs/operators';
import { environment } from './../../environments/environment';

export interface Team {
  id: number;
  name: string;
}

export interface Match {
  id: number;
  home_team: Team;
  away_team: Team;
  home_score: number;
  away_score: number;
  match_date: string;
  status: 'completed' | 'scheduled';
}

@Injectable({
  providedIn: 'root'
})
export class MatchService {
  private apiUrl = environment.API_URL;
  constructor(private http: HttpClient) {}

  getPendingMatches(): Observable<Match[]> {
    const params = new HttpParams().set('played', 'false');
    const apiUrl = environment.API_URL;

    return this.http.get<Match[]>(`${apiUrl}/matches`, { params })
      .pipe(
        map(matches => matches.map(match => ({
          ...match,
          status: 'scheduled' 
        })))
      );
  }

  updateMatchResult(matchId: number, homeScore: number, awayScore: number): Observable<any> {
    const url = `${this.apiUrl}/matches/${matchId}/result`;
    return this.http.post(url, {
      home_score: homeScore,
      away_score: awayScore
    });
  }
}