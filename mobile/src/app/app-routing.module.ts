import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    loadChildren: () => import('./tabs/tabs.module').then(m => m.TabsPageModule)
  },
  {
    path: 'matches',
    loadChildren: () => import('./pages/matches/matches.module').then( m => m.MatchesPageModule)
  },
  {
    path: 'report-result',
    loadChildren: () => import('./pages/report-result/report-result.module').then( m => m.ReportResultPageModule)
  }
];
@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule {}
