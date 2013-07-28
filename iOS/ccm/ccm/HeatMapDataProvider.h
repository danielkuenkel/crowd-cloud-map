//
//  HeatMapDataProvider.h
//  ccm
//
//  Created by Daniel Künkel on 20.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "ServiceConnector.h"

@protocol HeatMapDataProviderDelegate <NSObject>
-(void)receivedGeoData:(NSDictionary*)data;
@end

@interface HeatMapDataProvider : NSObject<ServiceConnectorDelegate>
@property (strong,nonatomic) id <HeatMapDataProviderDelegate> delegate;
+(HeatMapDataProvider *)sharedInstance;
-(void) getCurrentGeolocations;

@end
