//
//  ServiceConnector.h
//  ccm
//
//  Created by Daniel Künkel on 20.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import <Foundation/Foundation.h>
@protocol ServiceConnectorDelegate <NSObject>
-(void)requestReturnedData:(NSData*)data;
@end
@interface ServiceConnector : NSObject <NSURLConnectionDelegate,NSURLConnectionDataDelegate>
@property (strong,nonatomic) id <ServiceConnectorDelegate> delegate;
-(void)trackCurrentLocation:(double)latitude longitude:(double)longitude;
-(void)login:(NSString*)username password:(NSString*)password;
-(void)registerUser:(NSString*)username password:(NSString*)password;
-(void)getGeolocations;
@end
