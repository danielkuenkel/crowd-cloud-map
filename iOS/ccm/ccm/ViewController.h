//
//  ViewController.h
//  ccm
//
//  Created by Daniel Künkel on 13.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "ServiceConnector.h"

NSInteger const SIGN_IN = 0;
NSInteger const SIGN_UP = 1;
@interface ViewController : UIViewController <UITextFieldDelegate, ServiceConnectorDelegate>
@property (atomic) int state;
@property (weak, nonatomic) IBOutlet UITextField *username;
@property (weak, nonatomic) IBOutlet UITextField *password;
@property (weak, nonatomic) IBOutlet UIImageView *inputBackground;
@property (weak, nonatomic) IBOutlet UIButton *signUpButton;
@property (weak, nonatomic) IBOutlet UIButton *signInButton;
@property (weak, nonatomic) IBOutlet UIImageView *seperatorTop;
@property (weak, nonatomic) IBOutlet UIImageView *seperatorBottom;
-(BOOL)textFieldShouldReturn:(UITextField *)textField;
-(void)dismissKeyboard;
- (IBAction)onSignUpTap:(id)sender;
- (IBAction)onSignInTap:(id)sender;
- (IBAction)handeSwipeDown:(id)sender;
- (IBAction)handleSwipeUp:(id)sender;

@end
