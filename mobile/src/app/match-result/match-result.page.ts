import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Match, MatchService } from '../services/match.service';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-match-result',
  template: `
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-back-button></ion-back-button>
        </ion-buttons>
        <ion-title>Report Result</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content>
      <div *ngIf="match" class="ion-padding">
        <ion-card>
          <ion-card-header>
            <ion-card-title>{{ match.home_team.name }} vs {{ match.away_team.name }}</ion-card-title>
            <ion-card-subtitle>{{ match.match_date | date:'fullDate' }}</ion-card-subtitle>
          </ion-card-header>

          <ion-card-content>
            <form [formGroup]="resultForm" (ngSubmit)="submitResult()">
              <ion-grid>
                <ion-row class="ion-justify-content-center ion-align-items-center">
                  <ion-col size="4" class="ion-text-center">
                    <ion-text color="primary">
                      <h2>{{ match.home_team.name }}</h2>
                    </ion-text>
                    <ion-item>
                      <ion-input 
                        type="number" 
                        formControlName="homeScore" 
                        min="0"
                        class="ion-text-center"
                        placeholder="0">
                      </ion-input>
                    </ion-item>
                  </ion-col>

                  <ion-col size="4" class="ion-text-center">
                    <ion-text>
                      <h1>VS</h1>
                    </ion-text>
                  </ion-col>

                  <ion-col size="4" class="ion-text-center">
                    <ion-text color="secondary">
                      <h2>{{ match.away_team.name }}</h2>
                    </ion-text>
                    <ion-item>
                      <ion-input 
                        type="number" 
                        formControlName="awayScore" 
                        min="0"
                        class="ion-text-center"
                        placeholder="0">
                      </ion-input>
                    </ion-item>
                  </ion-col>
                </ion-row>

                <ion-row class="ion-justify-content-center ion-margin-top">
                  <ion-col size="12">
                    <ion-button 
                      type="submit" 
                      expand="block" 
                      [disabled]="resultForm.invalid"
                      color="primary">
                      Guardar Resultado
                    </ion-button>
                  </ion-col>
                </ion-row>
              </ion-grid>
            </form>
          </ion-card-content>
        </ion-card>
      </div>
    </ion-content>
  `
})
export class MatchResultPage implements OnInit {
  match: Match | null = null;
  resultForm: FormGroup;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private matchService: MatchService,
    private fb: FormBuilder,
    private alertController: AlertController
  ) {
    this.resultForm = this.fb.group({
      homeScore: ['', [Validators.required, Validators.min(0)]],
      awayScore: ['', [Validators.required, Validators.min(0)]]
    });
  }

  ngOnInit(): void {
    const matchId = this.route.snapshot.paramMap.get('id');
    if (matchId) {
      this.loadMatch(parseInt(matchId));
    }
  }

  loadMatch(matchId: number): void {
    this.matchService.getMatches().subscribe(matches => {
      this.match = matches.find(m => m.id === matchId) || null;
    });
  }

  async submitResult(): Promise<void> {
    if (this.resultForm.valid && this.match) {
      const { homeScore, awayScore } = this.resultForm.value;
      
      this.matchService.updateMatchResult(this.match.id, homeScore, awayScore).subscribe({
        next: () => {
          this.showSuccessAlert();
        },
        error: (error:any) => {
          this.showErrorAlert();
        }
      });
    }
  }

  async showSuccessAlert(): Promise<void> {
    const alert = await this.alertController.create({
      header: 'Éxito',
      message: 'Resultado guardado correctamente',
      buttons: [{
        text: 'OK',
        handler: () => {
          this.router.navigate(['/tabs/matches']);
        }
      }]
    });

    await alert.present();
  }

  async showErrorAlert(): Promise<void> {
    const alert = await this.alertController.create({
      header: 'Error',
      message: 'No se pudo guardar el resultado',
      buttons: ['OK']
    });

    await alert.present();
  }
}