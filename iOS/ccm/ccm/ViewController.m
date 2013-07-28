//
//  ViewController.m
//  ccm
//
//  Created by Daniel Künkel on 13.05.13.
//  Copyright (c) 2013 Daniel Künkel. All rights reserved.
//

#import "ViewController.h"
#import <QuartzCore/QuartzCore.h>
#import "JSONDictionaryExtensions.h"


@interface ViewController ()

@end

@implementation ViewController
@synthesize state;

- (void)viewDidLoad
{
    [super viewDidLoad];
    state = SIGN_IN;
    [_username setValue:[UIColor whiteColor] forKeyPath:@"_placeholderLabel.textColor"];
    [_password setValue:[UIColor whiteColor] forKeyPath:@"_placeholderLabel.textColor"];
}

-(void)viewDidAppear:(BOOL)animated {
//    [self switchToMap];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (void)dismissKeyboard
{
    [_username resignFirstResponder];
	[_password resignFirstResponder];
}

- (IBAction)onSignUpTap:(id)sender {
    if(state != SIGN_UP) {
        state = SIGN_UP;
        [_username setPlaceholder:@"Type in a username"];
        [_username setText:@""];
        [_password setPlaceholder:@"Password with 8 characters"];
        [_password setText:@""];
        [self animateToSignUp];
    }
    else {
        [self validateValues];
    }
}

- (IBAction)onSignInTap:(id)sender {
    if(state != SIGN_IN) {
        state = SIGN_IN;
        [_username setPlaceholder:@"Username"];
        [_username setText:@""];
        [_password setPlaceholder:@"Password"];
        [_password setText:@""];
        [self animateToSignIn];
    }
    else {
        [self validateValues];
    }
}

- (IBAction)handeSwipeDown:(id)sender {
    [self dismissKeyboard];
}

- (IBAction)handleSwipeUp:(id)sender {
    [_username becomeFirstResponder];
}

-(BOOL)textFieldShouldReturn:(UITextField *)textField
{
    if(textField == _username){
        [_password becomeFirstResponder];
    }
    if(textField == _password){
        [self validateValues];
    }
    return YES;
}

-(void)validateValues {
    if([self fieldsValid]) {
        switch (state) {
            case SIGN_UP:
                [self validateSignUp];
                break;
            
            default:
                [self validateSignIn];
                break;
        }
    }
    else
    {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Login" message:@"Please fill in all text fields." delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        [alert show];
    }
}

-(void)validateSignIn {
    [self dismissKeyboard];
    ServiceConnector *serviceConnector = [[ServiceConnector alloc] init];
    serviceConnector.delegate = self;
    [serviceConnector login:[_username.text lowercaseString] password:_password.text];
}
             
-(void)validateSignUp {
    [self dismissKeyboard];
    ServiceConnector *serviceConnector = [[ServiceConnector alloc] init];
    serviceConnector.delegate = self;
    [serviceConnector registerUser:[_username.text lowercaseString] password:_password.text];
}

-(void)requestReturnedData:(NSData *)data {
    
    NSDictionary *dictionary = [NSDictionary dictionaryWithJSONData:data];
    NSLog(@"%@", dictionary);
    if ([[dictionary objectForKey:@"success"] integerValue] == 1) {
        [self switchToMap];
    }
    else {
        UIAlertView *alert = nil;
        if(state == SIGN_IN) {
            alert = [[UIAlertView alloc] initWithTitle:@"Login failed" message:@"Service currently not available. Please try again later!" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
        }
        else if(state == SIGN_UP) {
            {
                NSLog(@"error: %@", [dictionary objectForKey:@"error"]);
                if([[dictionary objectForKey:@"error"] isEqualToString:@"namedTwice"]) {
                    alert = [[UIAlertView alloc] initWithTitle:@"Register failed" message:@"Please choose another username! This already exists." delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
                }
                else
                {
                    alert = [[UIAlertView alloc] initWithTitle:@"Register failed" message:@"Service currently not available. Please try again later!" delegate:self cancelButtonTitle:@"Close" otherButtonTitles:NULL, nil];
                }
            }
            
        }
        [alert show];
    }
}

-(BOOL)fieldsValid {
    NSString *trimedUsername  = [_username.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    NSString *trimedPassword  = [_password.text stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceAndNewlineCharacterSet]];
    
    if ([trimedUsername length] >= 6 && [trimedPassword length] >= 8)
    {
        return YES;
    }
    return NO;
}

-(void)switchToMap {
    [self performSegueWithIdentifier: @"LoggedInSeque" sender: self];
}

-(void)animateToSignIn
{
    [_username becomeFirstResponder];
    [UIView beginAnimations:@"animateToSignIn" context:NULL];
    
    _seperatorBottom.alpha = 0;
    _seperatorTop.alpha = 1;
    
    
    _inputBackground.frame = CGRectMake(_inputBackground.frame.origin.x,
                                        76+15,
                                        _inputBackground.frame.size.width,
                                        _inputBackground.frame.size.height);
    
    _username.frame = CGRectMake(_username.frame.origin.x,
                                 81+15,
                                 _username.frame.size.width,
                                 _username.frame.size.height);
    
    _password.frame = CGRectMake(_password.frame.origin.x,
                                 118+15,
                                 _password.frame.size.width,
                                 _password.frame.size.height);
    
    [UIView commitAnimations];
}

-(void)animateToSignUp
{
    [_username becomeFirstResponder];
    [UIView beginAnimations:@"animateToSignUp" context:NULL];

    _seperatorBottom.alpha = 1;
    _seperatorTop.alpha = 0;
    
    
    _inputBackground.frame = CGRectMake(_inputBackground.frame.origin.x,
                                        76,
                                        _inputBackground.frame.size.width,
                                        _inputBackground.frame.size.height);
    
    _username.frame = CGRectMake(_username.frame.origin.x,
                                 81,
                                 _username.frame.size.width,
                                 _username.frame.size.height);
    
    _password.frame = CGRectMake(_password.frame.origin.x,
                                 118,
                                 _password.frame.size.width,
                                 _password.frame.size.height);
    
    [UIView commitAnimations];
}


@end
