//
//  MapViewController.m
//  ccm
//
//  Created by Daniel Künkel on 16.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import "MapViewController.h"
#import "JSONDictionaryExtensions.h"
#import "HeatMapDataProvider.h"

@interface MapViewController ()

@end

@implementation MapViewController
{
    HeatMap *heatMap;
}
@synthesize mapView;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidAppear:(BOOL)animated {
    if (_userLocation != nil) {
        [self updateUserLocation:_userLocation];
    }
}

- (void)viewDidLoad
{
    isFirstMapInit = YES;
    [super viewDidLoad];
    
    [self refreshGeolocations];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)mapView:(MKMapView *)mapView didUpdateUserLocation:(MKUserLocation *)userLocation
{
    [self setUserLocation:userLocation];
    [self updateUserLocation:userLocation];
}

- (void) updateUserLocation:(MKUserLocation *)userLocation
{
    if(isFirstMapInit == YES)
    {
        isFirstMapInit = NO;
        MKCoordinateRegion region = MKCoordinateRegionMakeWithDistance(userLocation.coordinate, 800, 800);
        [self.mapView setRegion:region animated:YES];
    }
}

- (IBAction)onLocateTap:(id)sender {
    MKCoordinateRegion region = MKCoordinateRegionMakeWithDistance(mapView.userLocation.coordinate, 800, 800);
    [self.mapView setRegion:region animated:YES];
}

- (IBAction)mapTypeChanged:(id)sender {
    switch (_mapStyleSegment.selectedSegmentIndex) {
        case 1:
            [mapView setMapType:MKMapTypeSatellite];
            break;
            
        case 2:
            [mapView setMapType:MKMapTypeHybrid];
            break;
            
        default:
            [mapView setMapType:MKMapTypeStandard];
            break;
    }
}

- (IBAction)onTrackTap:(id)sender {
    ServiceConnector *serviceConnector = [[ServiceConnector alloc] init];
    serviceConnector.delegate = self;
    [serviceConnector trackCurrentLocation:_userLocation.coordinate.latitude longitude:_userLocation.coordinate.longitude];
}

-(void)requestReturnedData:(NSData *)data {
    
    NSDictionary *dictionary = [NSDictionary dictionaryWithJSONData:data];
    
    if([[dictionary objectForKey:@"success"] integerValue] == 1) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Track success" message:@"Your current geolocation has been tracked successfully." delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
        
        [self refreshGeolocations];
    }
    else if([[dictionary objectForKey:@"tracks"] integerValue] >= 2) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Track failed" message:@"Maximum number of tracks reached. Please try again tomorrow!" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
    else {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Track failed" message:@"Service currently not available. Please try again later!" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
}

-(void)refreshGeolocations {
    HeatMapDataProvider *dataProvider = [HeatMapDataProvider sharedInstance];
    dataProvider.delegate = self;
    [dataProvider getCurrentGeolocations];
}

-(void)receivedGeoData:(NSDictionary*)data {
    if(!heatMap)
    {
        heatMap = [[HeatMap alloc] initWithData:data];
        [self.mapView addOverlay:heatMap];
        [self.mapView setVisibleMapRect:[heatMap boundingMapRect] animated:YES];
        
        if (_userLocation != nil) {
            isFirstMapInit = YES;
            [self updateUserLocation:_userLocation];
        }
    }
    else
    {
        [heatMap setData:data];
    }
}

#pragma mark -
#pragma mark MKMapViewDelegate

- (MKOverlayView *)mapView:(MKMapView *)mapView viewForOverlay:(id <MKOverlay>)overlay
{
    return [[HeatMapView alloc] initWithOverlay:overlay];
}

@end
