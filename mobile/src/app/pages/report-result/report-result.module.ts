import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { ReportResultPageRoutingModule } from './report-result-routing.module';

import { ReportResultPage } from './report-result.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    ReportResultPage,
    ReportResultPageRoutingModule
  ],
  declarations: []
})
export class ReportResultPageModule {}
