import { Component, OnInit } from '@angular/core';
import { Standing, StandingsService } from './../../../services/standings.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-standings-list',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="row">
      <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div>
            <h2 class="section-title">
              Clasificación
            </h2>
          </div>
        </div>

        <!-- Tabla de Clasificación -->
        <div class="card standings-card">
          <div class="card-header bg-dark text-white">
            <div class="row align-items-center">
              <div class="col-md-6">
                <h5 class="mb-0">
                  Tabla de Posiciones
                </h5>
              </div>
            </div>
          </div>
          
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Equipo</th>
                    <th width="100" class="text-center">PJ</th>
                    <th width="100" class="text-center">GF</th>
                    <th width="100" class="text-center">GC</th>
                    <th width="100" class="text-center">DG</th>
                    <th width="120" class="text-center">Puntos</th>
                  </tr>
                </thead>
                <tbody>
                  <tr *ngFor="let standing of standings; let i = index" 
                      [class]="getRowClass(i)">
                    <!-- Equipo -->
                    <td>
                      <div class="d-flex align-items-center">
                        <div>
                          <h6 class="mb-0 fw-bold team-name">{{ standing.team_name }}</h6>
                        </div>
                      </div>
                    </td>
                    
                    <!-- Partidos Jugados -->
                    <td class="text-center">
                      <span class="stats-value">{{ standing.played || 0 }}</span>
                    </td>
                    
                    <!-- Goles a Favor -->
                    <td class="text-center">
                      <span class="goals-for">{{ standing.goals_for || 0 }}</span>
                    </td>
                    
                    <!-- Goles en Contra -->
                    <td class="text-center">
                      <span class="goals-against">{{ standing.goals_against || 0 }}</span>
                    </td>
                    
                    <!-- Diferencia de Goles -->
                    <td class="text-center">
                      <span class="goal-diff" [class]="getGoalDiffClass(standing.goal_difference)">
                        {{ getFormattedGoalDifference(standing.goal_difference) }}
                      </span>
                    </td>
                    
                    <!-- Puntos -->
                    <td class="text-center">
                      <span class="points-badge">
                        <strong>{{ standing.points || 0 }}</strong>
                      </span>
                    </td>
                  </tr>
                  
                  <!-- Estado vacío -->
                  <tr *ngIf="standings.length === 0">
                    <td colspan="7" class="text-center py-5">
                      <div class="empty-state">
                        <i class="fas fa-table fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay datos de clasificación</h5>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .section-title {
      color: #2c3e50;
      padding-bottom: 10px;
      margin-bottom: 5px;
    }

    .standings-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .legend {
      display: flex;
      gap: 15px;
      justify-content: flex-end;
    }

    .legend-item {
      display: flex;
      align-items: center;
      font-size: 0.8rem;
      color: rgba(255, 255, 255, 0.9);
    }

    .legend-color {
      width: 12px;
      height: 12px;
      border-radius: 2px;
      margin-right: 5px;
    }

    .legend-color.champions {
      background: linear-gradient(45deg, #4CAF50, #45a049);
    }

    .legend-color.europe {
      background: linear-gradient(45deg, #2196F3, #1976D2);
    }

    .legend-color.relegation {
      background: linear-gradient(45deg, #f44336, #d32f2f);
    }

    .position-badge {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 0.9rem;
    }

    .position-champions {
      background: linear-gradient(45deg, #4CAF50, #45a049);
      color: white;
    }

    .position-europe {
      background: linear-gradient(45deg, #2196F3, #1976D2);
      color: white;
    }

    .position-relegation {
      background: linear-gradient(45deg, #f44336, #d32f2f);
      color: white;
    }

    .position-normal {
      background: #f8f9fa;
      color: #6c757d;
      border: 1px solid #dee2e6;
    }

    .team-logo {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    .team-name {
      color: #2c3e50;
    }

    .stats-value {
      font-weight: 600;
      color: #2c3e50;
      font-size: 1.1rem;
    }

    .goals-for {
      font-weight: 600;
      color: #27ae60;
      font-size: 1.1rem;
    }

    .goals-against {
      font-weight: 600;
      color: #e74c3c;
      font-size: 1.1rem;
    }

    .goal-diff {
      font-weight: 600;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.9rem;
    }

    .goal-diff.positive {
      background: rgba(39, 174, 96, 0.1);
      color: #27ae60;
    }

    .goal-diff.negative {
      background: rgba(231, 76, 60, 0.1);
      color: #e74c3c;
    }

    .goal-diff.neutral {
      background: rgba(108, 117, 125, 0.1);
      color: #6c757d;
    }

    .points-badge {
      background: linear-gradient(45deg, #3498db, #2980b9);
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 1rem;
    }

    .row-champions {
      background: linear-gradient(90deg, rgba(76, 175, 80, 0.05), transparent) !important;
      border-left: 4px solid #4CAF50;
    }

    .row-europe {
      background: linear-gradient(90deg, rgba(33, 150, 243, 0.05), transparent) !important;
      border-left: 4px solid #2196F3;
    }

    .row-relegation {
      background: linear-gradient(90deg, rgba(244, 67, 54, 0.05), transparent) !important;
      border-left: 4px solid #f44336;
    }

    .stat-card {
      padding: 15px;
    }

    .stat-value {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .stat-label {
      font-size: 0.9rem;
      color: #6c757d;
      font-weight: 500;
    }

    .empty-state {
      padding: 40px 20px;
    }

    tr:hover {
      background-color: rgba(52, 152, 219, 0.03) !important;
    }
  `]
})
export class StandingsListComponent implements OnInit {
  standings: Standing[] = [];
  currentDate: string;
  lastUpdated: string;
  currentMatchday: number = 15;

  constructor(private standingsService: StandingsService) {
    this.currentDate = new Date().toLocaleDateString('es-ES');
    this.lastUpdated = new Date().toLocaleTimeString('es-ES', { 
      hour: '2-digit', 
      minute: '2-digit' 
    });
  }

  ngOnInit(): void {
    this.loadStandings();
  }

  loadStandings(): void {
    this.standingsService.getStandings().subscribe((standings: Standing[]) => {
      this.standings = standings;
      this.lastUpdated = new Date().toLocaleTimeString('es-ES', { 
        hour: '2-digit', 
        minute: '2-digit' 
      });
    });
  }

  getRowClass(index: number): string {
    if (index < 4) return 'row-champions';
    if (index < 6) return 'row-europe';
    if (index >= this.standings.length - 3) return 'row-relegation';
    return '';
  }

  getPositionClass(index: number): string {
    if (index < 4) return 'position-champions';
    if (index < 6) return 'position-europe';
    if (index >= this.standings.length - 3) return 'position-relegation';
    return 'position-normal';
  }

  getGoalDiffClass(goalDifference: number): string {
    if (goalDifference > 0) return 'positive';
    if (goalDifference < 0) return 'negative';
    return 'neutral';
  }

  getFormattedGoalDifference(goalDifference: number): string {
    const diff = goalDifference || 0;
    return diff > 0 ? `+${diff}` : diff.toString();
  }

  getTeamColor(teamName: string): string {
    const colors = ['#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6', '#1abc9c', '#34495e', '#e67e22'];
    const index = teamName.charCodeAt(0) % colors.length;
    return colors[index];
  }

  getTeamForm(teamName: string): string {
    const forms = ['✅ ✅ ❌', '✅ ❌ ✅', '❌ ✅ ✅', '✅ ❌ ❌', '❌ ❌ ✅'];
    const index = teamName.length % forms.length;
    return forms[index];
  }

  // Métodos estadísticos
  getTotalGoals(): number {
    return this.standings.reduce((total, standing) => 
      total + (standing.goals_for || 0), 0);
  }

  getBestAttack(): string {
    if (this.standings.length === 0) return '-';
    const best = this.standings.reduce((prev, current) => 
      (prev.goals_for || 0) > (current.goals_for || 0) ? prev : current);
    return best.team_name;
  }

  getBestDefense(): string {
    if (this.standings.length === 0) return '-';
    const best = this.standings.reduce((prev, current) => 
      (prev.goals_against || 0) < (current.goals_against || 0) ? prev : current);
    return best.team_name;
  }

  getAveragePoints(): string {
    if (this.standings.length === 0) return '0';
    const average = this.standings.reduce((sum, standing) => 
      sum + (standing.points || 0), 0) / this.standings.length;
    return average.toFixed(1);
  }
}