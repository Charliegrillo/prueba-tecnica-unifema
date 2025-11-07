import { Component, OnInit } from '@angular/core';
import { Match, MatchService } from '../services/match.service';
import { AlertController } from '@ionic/angular';

@Component({
  selector: 'app-tab2',
  templateUrl: 'matches.page.html',
  styleUrls: ['matches.page.scss'],
  standalone: false,
})
export class MatchesPage implements OnInit {
  scheduledMatches: Match[] = [];
  isLoading: boolean = true;
  error: string = ''

  constructor(
    private matchService: MatchService,
    private alertController: AlertController
  ) {}

  ngOnInit(): void {
    this.loadScheduledMatches();
  }

  loadScheduledMatches(): void {
    this.isLoading = true;
    this.error = '';
    
    this.matchService.getPendingMatches().subscribe({
      next: (matches) => {
        this.scheduledMatches = matches.filter(match => match.status === 'scheduled');
        this.isLoading = false;
      },
      error: (err) => {
        this.error = 'Error loading matches: ' + err.message;
        this.isLoading = false;
        
        this.showErrorAlert('Connection error', 
          `Could not connect to the server. 
          Check that you are on the same WiFi network. 
          Error ${err.status} - ${err.message}`);
      }
    });
  }

  async reportMatchResult(match: Match): Promise<void> {
    const alert = await this.alertController.create({
      header: `Report Result: ${match.home_team.name} vs ${match.away_team.name}`,
      inputs: [
        {
          name: 'homeScore',
          type: 'number',
          placeholder: `Goles ${match.home_team.name}`,
          min: 0,
          value: '0'
        },
        {
          name: 'awayScore',
          type: 'number',
          placeholder: `Goles ${match.away_team.name}`,
          min: 0,
          value: '0'
        }
      ],
      buttons: [
        {
          text: 'Cancel',
          role: 'cancel'
        },
        {
          text: 'Save',
          handler: (data) => {
            this.saveMatchResult(match.id, parseInt(data.homeScore), parseInt(data.awayScore));
          }
        }
      ]
    });

    await alert.present();
  }

  saveMatchResult(matchId: number, homeScore: number, awayScore: number): void {
    this.matchService.updateMatchResult(matchId, homeScore, awayScore).subscribe({
      next: () => {
        this.showSuccessAlert();
        this.loadScheduledMatches(); // Recargar la lista
      },
      error: (err) => {
        let errorMessage = 'The result could not be saved.';
        if (err.status === 0) {
          errorMessage = 'Connection error. Check your WiFi network.';
        } else if (err.status === 404) {
          errorMessage = 'Match not found on the server';
        } else if (err.status === 400) {
          errorMessage = 'Invalid data for the result';
        } else if (err.status === 500) {
          errorMessage = 'Server error';
        }        
         this.showErrorAlert('Error saving', errorMessage);
        console.error('Error saving match result:', err);
      }
    });
  }

  async showSuccessAlert(): Promise<void> {
    const alert = await this.alertController.create({
      header: 'Success',
      message: 'Result reported correctly',
      buttons: ['OK']
    });
    await alert.present();
  }

  async showErrorAlert(header: string, message: string): Promise<void> {
    const alert = await this.alertController.create({
      header: header,
      message: message,
      buttons: ['OK']
    });
    await alert.present();
  }

  handleRefresh(event: any): void {
    this.loadScheduledMatches();
    setTimeout(() => {
      event.target.complete();
    }, 1000);
  }
  
}