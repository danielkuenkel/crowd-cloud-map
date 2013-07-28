//
//  MapViewController.h
//  ccm
//
//  Created by Daniel Künkel on 16.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>
#import <CoreLocation/CoreLocation.h>
#import "ServiceConnector.h"
#import "HeatMapDataProvider.h"
#import "HeatMap.h"
#import "HeatMapView.h"

@interface MapViewController : UIViewController <MKMapViewDelegate, ServiceConnectorDelegate, HeatMapDataProviderDelegate>
{
    BOOL isFirstMapInit;
}
@property (weak, nonatomic) IBOutlet MKMapView *mapView;
@property(retain, nonatomic) MKUserLocation *userLocation;
- (IBAction)onLocateTap:(id)sender;
@property (weak, nonatomic) IBOutlet UISegmentedControl *mapStyleSegment;
- (IBAction)mapTypeChanged:(id)sender;
- (IBAction)onTrackTap:(id)sender;

@end
