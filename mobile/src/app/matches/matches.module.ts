import { IonicModule } from '@ionic/angular';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { MatchesPage } from './matches.page';
import { MatchesPageRoutingModule } from './matches-routing.module';

@NgModule({
  imports: [
    IonicModule,
    CommonModule,
    FormsModule,
    MatchesPageRoutingModule
  ],
  declarations: [MatchesPage]
})
export class MatchesPageModule {}
