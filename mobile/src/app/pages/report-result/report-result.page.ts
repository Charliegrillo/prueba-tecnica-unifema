import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { IonicModule } from '@ionic/angular';
import { RouterModule } from '@angular/router';

@Component({
  standalone: true, 
  selector: 'app-report-result',
  templateUrl: './report-result.page.html',
  styleUrls: ['./report-result.page.scss'],
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    RouterModule
  ]  
})
export class ReportResultPage implements OnInit {

  constructor() { }

  ngOnInit() {
  }

}
