//
//  HeatMapDataProvider.m
//  ccm
//
//  Created by Daniel Künkel on 20.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import "HeatMapDataProvider.h"
#import "JSONDictionaryExtensions.h"
#import <MapKit/MapKit.h>

@implementation HeatMapDataProvider

static HeatMapDataProvider *sharedInstance = nil;

// Get the shared instance and create it if necessary.
+ (HeatMapDataProvider *)sharedInstance {
    if (sharedInstance == nil) {
        sharedInstance = [[super allocWithZone:NULL] init];
    }
    
    return sharedInstance;
}

-(void) getCurrentGeolocations {
    
    ServiceConnector *serviceConnector = [[ServiceConnector alloc] init];
    serviceConnector.delegate = self;
    [serviceConnector getGeolocations];
}

-(void)requestReturnedData:(NSData *)data {
    
    NSError *error;
    NSString *jsonString = [[NSString alloc] initWithData:data encoding:NSUTF8StringEncoding];
    NSDictionary *JSON = [NSJSONSerialization JSONObjectWithData: [jsonString dataUsingEncoding:NSUTF8StringEncoding] options: NSJSONReadingMutableContainers error: nil];
    NSMutableDictionary *result = [[NSMutableDictionary alloc] initWithCapacity:[JSON count]];

    for (NSDictionary *item in JSON) {
        
        MKMapPoint point = MKMapPointForCoordinate(CLLocationCoordinate2DMake([[item objectForKey:@"lat"] doubleValue],
                                                                              [[item objectForKey:@"long"] doubleValue]));
        
        NSValue *pointValue = [NSValue value:&point withObjCType:@encode(MKMapPoint)];
        [result setObject:[NSNumber numberWithInt:1] forKey:pointValue];
    }
    if (error) {
        NSLog(@"%s JSONObjectWithData error %@", __FUNCTION__, error);
    }

    [self.delegate receivedGeoData:result];
}

@end
